<?php

use Teknomavi\Kargo\Company\ServiceAbstract;
use Teknomavi\Kargo\Company\ServiceInterface;
use Teknomavi\Kargo\Company\Yurtici\Helper\ShipmentInfo;
use Teknomavi\Kargo\Model\Package;
use Teknomavi\Kargo\Response\ShipmentStatus;
use Teknomavi\Kargo\Response\CreateShipment;

class Service extends ServiceAbstract implements ServiceInterface
{
    private $PttSoapClient;
    /**
     * @var array
     */
    protected $statusMapping = [
        '0' => ShipmentStatus::STATUS_NOT_PROCESSED,
        '1' => ShipmentStatus::STATUS_ON_DISTRIBUTION,
        '2' => ShipmentStatus::STATUS_PACKAGE_SCANNED,
        '3' => ShipmentStatus::STATUS_EXCEPTION,
        '4' => ShipmentStatus::STATUS_RETURN_BACK,
        'Teslim Edildi' => ShipmentStatus::STATUS_DELIVERED,
    ];

    public function __construct($options)
    {
        $this->PttSoapClient = new PttSoapClient($options);
    }

    public function sendPackages()
    {
        $responses = [];
        foreach ($this->packages as $package) {
            $package = ShipmentInfo::generatePackage($package);
            $res = $this->PttSoapClient->createShipment(
                $package->getConsigneeName(),
                $package->getConsigneeAddress(),
                $package->getConsigneeCity(),
                $package->getConsigneeTown(),
                $package->getConsigneeMobilPhone()
            );
            $response = new CreateShipment();
            if (is_array($res) && $res['hataKodu'] == 1) {
                $response->setSuccess(true);
                $response->setTrackingNumber($res["dongu"]->barkod);
                $response->setLabelLinks($res["dongu"]->donguAciklama);
                $response->setReferenceNumber($this->PttSoapClient->getFileName());
            } else {
                $response->setSuccess(false);
                $response->setErrorDescription($res['aciklama']);
            }
            $responses[$package->getReferenceNo()] = $response;
        }
        return $responses;
    }

    public function addPackage(Package $package)
    {
        $package = ShipmentInfo::generateFromPackage($package);
        array_push($this->packages, $package);
    }

    /**
     * @param string $trackingNumber
     *
     * @return ShipmentStatus
     * @throws \Teknomavi\Kargo\Exception\InvalidParameterValue
     */
    public function getShipmentStatusByTrackingNumber(string $trackingNumber): ShipmentStatus
    {
        $shipmentStatus = new ShipmentStatus();
        $shipmentStatus->setTrackingNumber($trackingNumber);
        $res = $this->PttSoapClient->queryShipment($trackingNumber);
        $ok = $this->isErrorMessage($res, $shipmentStatus);
        if ($ok) {
            return $shipmentStatus;
        }
        // set the status code
        if (property_exists($res->ShippingDeliveryVO->shippingDeliveryDetailVO, 'operationCode')) {
            $opCode = $res->ShippingDeliveryVO->shippingDeliveryDetailVO->operationCode;
            $opMsg = $res->ShippingDeliveryVO->shippingDeliveryDetailVO->operationMessage;
            $shipmentStatus->setStatusDetails($opMsg);
            $shipmentStatus->setOriginalStatus($opCode);

            try {
                $shipmentStatus->setStatusCode($this->mapStatus($opCode));
            } catch (InvalidParameterValue $a) {
                $shipmentStatus->setStatusCode(ShipmentStatus::STATUS_EXCEPTION);
            }
        }
        // makes sure there is such a field
        if (!property_exists($res->ShippingDeliveryVO->shippingDeliveryDetailVO, 'shippingDeliveryItemDetailVO')) {
            return $shipmentStatus;
        }
        // Update Time from document Date and Document Time
        if (property_exists($res->ShippingDeliveryVO->shippingDeliveryDetailVO->shippingDeliveryItemDetailVO, 'documentDate')) {
            $date = $res->ShippingDeliveryVO->shippingDeliveryDetailVO->shippingDeliveryItemDetailVO->documentDate;
            $time = $res->ShippingDeliveryVO->shippingDeliveryDetailVO->shippingDeliveryItemDetailVO->documentTime;
            $shipmentStatus->setUpdateTime(\DateTime::createFromFormat('YmdGis', $date . $time, new \DateTimeZone('Asia/Istanbul')));
        }
        $this->addTrackingNumber($res, $shipmentStatus);

        return $shipmentStatus;
    }
}