<?php
namespace Teknomavi\Kargo\Company\Ups;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Teknomavi\Kargo\Company\ServiceAbstract;
use Teknomavi\Kargo\Company\ServiceInterface;
use Teknomavi\Kargo\Company\Ups\Helper\CreateShipment\CreateShipment;
use Teknomavi\Kargo\Company\Ups\Helper\CreateShipment\CreateShipment_Type2;
use Teknomavi\Kargo\Company\Ups\Helper\CreateShipment\Login_Type1;
use Teknomavi\Kargo\Company\Ups\Helper\CreateShipment\ShipmentInfo_Type2;
use Teknomavi\Kargo\Company\Ups\Helper\Mapper;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\ArrayOfString;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\GetPackageInfoByReferance_V1;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\GetPackageInfoByTrackingNumber_V1;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\GetTransactionsByList_V1;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\GetTransactionsByPackagePickupDate_V1;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\PackageInformation;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\PackageTransaction;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\PackageTransactionwithDeliveryDetail;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\ParamReferansList;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\QueryPackageInfo;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\ReferansTypes;
use Teknomavi\Kargo\Company\Ups\Helper\QueryPackageInfo\TransactionTypes;
use Teknomavi\Kargo\Exception\MethodNotSupported;
use Teknomavi\Kargo\Model\Package;
use Teknomavi\Kargo\Response\PackageInfo;
use Teknomavi\Kargo\Response\ShipmentStatus;

class Service extends ServiceAbstract implements ServiceInterface
{
    /**
     * @var array
     */
    protected $statusMapping = [
        1  => ShipmentStatus::STATUS_PACKAGE_SCANNED, //GİRİŞ SCAN EDİLDİ
        2  => ShipmentStatus::STATUS_DELIVERED, //ALICIYA TESLİM EDİLDİ
        3  => ShipmentStatus::STATUS_EXCEPTION, //ÖZEL DURUM OLUŞTU
        4  => ShipmentStatus::STATUS_ON_DISTRIBUTION, //KURYE DAĞITMAK ÜZERE ÇIKARDI
        5  => ShipmentStatus::STATUS_ON_BRANCH, //KURYE GERİ GETİRDİ
        6  => ShipmentStatus::STATUS_ON_BRANCH, //ŞUBEYE GÖNDERİLDİ
        7  => ShipmentStatus::STATUS_ON_BRANCH, //ŞUBEDEN GELDİ
        12 => ShipmentStatus::STATUS_ON_BRANCH, //K. KONTEYNERE KONDU
        15 => ShipmentStatus::STATUS_ON_BRANCH, //MANİFESTO FAZLASI
        16 => ShipmentStatus::STATUS_ON_BRANCH, //K. KONTEYNERDEN ÇIKTI
        17 => ShipmentStatus::STATUS_RETURN_BACK, //GÖNDERENE İADE AMAÇLI ÇIKIŞ
        18 => ShipmentStatus::STATUS_PACKAGE_SCANNED, //MÜŞTERİ TOPLU GİRİŞ
        19 => ShipmentStatus::STATUS_ON_BRANCH, //ŞUBEDE BEKLEYEN
        30 => ShipmentStatus::STATUS_ON_BRANCH, //KONSOLOSLUKTAN TESLİM ALINDI
        31 => ShipmentStatus::STATUS_PACKAGE_SCANNED, //ÇAĞRI SONUCU ALINDI
        32 => ShipmentStatus::STATUS_ON_BRANCH, //DEPOYA GİRDİ
        33 => ShipmentStatus::STATUS_ON_BRANCH, //DEPODAN ÇIKTI
        34 => ShipmentStatus::STATUS_ON_BRANCH, //EDI BİLGİ TRANSFER
        35 => ShipmentStatus::STATUS_PACKAGE_SCANNED, //MÜŞTERİ DEPODA OKUNDU
        36 => ShipmentStatus::STATUS_ON_DISTRIBUTION, //TOPLU DAĞITIMA ÇIKIŞ
        37 => ShipmentStatus::STATUS_ON_BRANCH, //TRANSİT KARŞILAMA
        38 => ShipmentStatus::STATUS_ON_BRANCH, //TRANSİT ÇIKIŞ
    ];
    /**
     * @var array
     */
    protected $packageTypeMapping = [
        'KOLİ' => PackageInfo::PACKAGE_TYPE_BOX,
        'ZARF' => PackageInfo::PACKAGE_TYPE_BOX,
    ];
    /**
     * @var array
     */
    protected $shipmentTypeMapping = [
        'NORMAL'  => PackageInfo::SHIPMENT_TYPE_NORMAL,
        'EXPRESS' => PackageInfo::SHIPMENT_TYPE_EXPRESS,
    ];
    /**
     * @var array
     */
    protected $paymentTypeMapping = [
        'GÖNDEREN ÖDEMELİ-PP' => PackageInfo::PAYMENT_TYPE_SENDER,
    ];
    /**
     * @var CreateShipment
     */
    private $shipmentService = null;
    /**
     * @var QueryPackageInfo
     */
    private $queryService = null;
    /**
     * @var string
     */
    private $sessionId = null;

    public function addPackage(Package $package)
    {
        $shipperCityResult = Mapper::getCityCode($package->getShipperCity());
        if (!$shipperCityResult["status"]) {
            return $shipperCityResult;
        }
        $shipperCityCode = $shipperCityResult["data"];

        $shipperTownResult = Mapper::getAreaCode($shipperCityCode, $package->getShipperTown());
        if (!$shipperTownResult["status"]) {
            return $shipperTownResult;
        }
        $shipperTownCode = $shipperTownResult["data"];

        $consigneeCityResult = Mapper::getCityCode($package->getConsigneeCity());
        if (!$consigneeCityResult["status"]) {
            return $consigneeCityResult;
        }
        $consigneeCityCode = $consigneeCityResult["data"];

        $consigneeTownResult = Mapper::getAreaCode($consigneeCityCode, $package->getConsigneeTown());
        if (!$consigneeTownResult["status"]) {
            return $consigneeTownResult;
        }
        $consigneeTownCode = $consigneeTownResult["data"];

        $shipmentType = new ShipmentInfo_Type2();
        $shipmentType
            ->setServiceLevel(3)
            ->setIdControlFlag(0)
            ->setPhonePrealertFlag(0)
            ->setSmsToConsignee(0)
            ->setSmsToShipper(0)
            ->setNumberOfPackages($package->getNumberOfPackages())
            ->setShipperAreaCode($shipperTownCode)
            ->setShipperCityCode($shipperCityCode)
            ->setShipperAddress($package->getShipperAddress())
            ->setShipperName($package->getShipperName())
            ->setShipperAccountNumber($package->getShipperAccountNumber())
            ->setConsigneeName(mb_substr($package->getConsigneeName(), 0, 40))
            ->setConsigneePostalCode($package->getConsigneePostalCode())
            ->setConsigneeAddress($package->getConsigneeAddress())
            ->setConsigneeAreaCode($consigneeTownCode)
            ->setConsigneeCityCode($consigneeCityCode)
            ->setConsigneePhoneNumber($package->getConsigneeMobilPhone())
            ->setCustomerReferance($package->getReferenceNo())
            ->setCustomerInvoiceNumber($package->getInvoiceNo())
            ->setInsuranceValue(0)
            ->setValueOfGoods(0)
            ->setValueOfGoodsCurrency('TL')
            ->setInsuranceValue(0)
            ->setInsuranceValueCurrency('TL');

        $shipmentType->setValueOfGoodsPaymentType(0);

        if ($package->getPaymentType() == Package::PAYMENT_TYPE_SHIPPER_PAY) {
            $shipmentType->setPaymentType(2);
        } else {
            $shipmentType->setPaymentType(1);
        }

        if ($package->getInsuranceValue() > 0) {
            $shipmentType->setInsuranceValue($package->getInsuranceValue());
            $shipmentType->setInsuranceValueCurrency('TL');
        }

        if ($package->getPackageType() == Package::PACKAGE_TYPE_BOX) {
            $shipmentType->setPackageType('K');
        } else {
            $shipmentType->setPackageType('L');
        }

        if (!$package->getShipmentType() == Package::SHIPMENT_TYPE_PREPAID) {
            $shipmentType->setValueOfGoods($package->getValueOfGoods());
            $shipmentType->setValueOfGoodsCurrency($package->getValueOfGoodsCurrency());
            if ($package->getShipmentType() == Package::SHIPMENT_TYPE_CARD_ON_DELIVERY) {
                $shipmentType->setValueOfGoodsPaymentType(2);
            } elseif ($package->getShipmentType() == Package::SHIPMENT_TYPE_CASH_ON_DELIVERY) {
                $shipmentType->setValueOfGoodsPaymentType(1);
            }
        }
        $this->packages[] = $shipmentType;
    }

    public function sendPackages()
    {
        $response = [];
        $service = $this->initShipmentService();
        foreach ($this->packages as $package) {
            $createShipmentResponse = new \Teknomavi\Kargo\Response\CreateShipment();
            $createShipmentResponse->setReferenceNumber($package->getCustomerReferance());
            /** @var ShipmentInfo_Type2 $package */
            $obj = new CreateShipment_Type2($this->getSessionId(), $package, true, true);

            try {
                $result = $service->CreateShipment_Type2($obj)->getCreateShipment_Type2Result();
                $trackingNumber = $result->getShipmentNo();
                if (!empty($trackingNumber)) {
                    $createShipmentResponse
                        ->setTrackingNumber($trackingNumber)
                        ->setSuccess(true)
                        ->setLabelLinks([$result->getLinkForLabelPrinting()])
                        ->setLabelStrings($result->getBarkodArrayPng()->getString());
                } else {
                    $createShipmentResponse
                        ->setErrorCode($result->getErrorCode())
                        ->setErrorDescription($result->getErrorDefinition())
                        ->setSuccess(false);
                }
            } catch (\Exception $exception) {
                $createShipmentResponse
                    ->setErrorCode('SOAP' . $exception->getCode())
                    ->setErrorDescription($exception->getMessage())
                    ->setSuccess(false);
            }
            $response[$package->getCustomerReferance()] = $createShipmentResponse;
        }

        return $response;
    }

    /**
     * @param string $trackingNumber
     *
     * @throws \Exception
     *
     * @return PackageInfo
     */
    public function getPackageInfoByTrackingNumber(string $trackingNumber): PackageInfo
    {
        $service = $this->initQueryService();
        $resultWebService = $service->GetPackageInfoByTrackingNumber_V1(
            new GetPackageInfoByTrackingNumber_V1(
                $this->getSessionId(),
                1,
                $trackingNumber
            )
        );
        $response = [];
        foreach ($resultWebService->getGetPackageInfoByTrackingNumber_V1Result() as $item) {
            if ($item instanceof PackageInformation) {
                $packageInfo = $this->populatePackageInfoFromItem($item);
                $response[$packageInfo->getTrackingNumber()] = $packageInfo;
            }
        }

        return current($response);
    }

    private function initQueryService(): QueryPackageInfo
    {
        if (!$this->queryService) {
            $this->queryService = new QueryPackageInfo([
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            ]);
        }

        return $this->queryService;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    private function getSessionId()
    {
        if (is_null($this->sessionId)) {
            $result = $this->initShipmentService()->Login_Type1(
                new Login_Type1($this->options['customerCode'], $this->options['username'], $this->options['password'])
            );
            if ($result->getLogin_Type1Result()->getErrorCode() != 0) {
                throw new \Exception(
                    $result->getLogin_Type1Result()->getErrorDefinition(),
                    'UPS-' . $result->getLogin_Type1Result()->getErrorCode()
                );
            }
            $this->sessionId = $result->getLogin_Type1Result()->getSessionID();
        }

        return $this->sessionId;
    }

    private function initShipmentService(): CreateShipment
    {
        if (!$this->shipmentService) {
            $this->shipmentService = new CreateShipment([
                'trace'              => 1,
                'connection_timeout' => 60,
                'features'           => SOAP_SINGLE_ELEMENT_ARRAYS,
            ]);
        }

        return $this->shipmentService;
    }

    /*
     * Courier
     */
    /*
     * Package Management
     */

    /**
     * @param PackageInformation $item
     *
     * @throws \Teknomavi\Kargo\Exception\InvalidParameterValue
     *
     * @return PackageInfo
     */
    private function populatePackageInfoFromItem($item): PackageInfo
    {
        $packageInfo = new PackageInfo();
        $packageInfo->setTrackingNumber($item->getTrackingNumber())
            ->setReferenceNumber($item->getCustomerReferance())
            ->setPackageType($this->mapPackageType($item->getPackageType()))
            ->setShipmentType($this->mapShipmentType($item->getShipmentType()))
            ->setPaymentType($this->mapPaymentType($item->getPaymentType()))
            ->setNumberOfPackages($item->getNumberOfPackages())
            ->setWeight($item->getActualWeight())
            ->setDesi($item->getVolumeWeight())
            ->setShipmentCost($item->getFreight())
            ->setShipmentCostCurrency($item->getFreightCurrency())
            ->setInsuranceValue($item->getInsuranceValue())
            ->setInsuranceValueCurrency($item->getInsuranceValueCurrency())
            ->setValueOfGoods($item->getValueOfGoods())
            ->setValueOfGoodsCurrency($item->getValueOfGoodsCurrency())
            ->setCreateTime(\DateTime::createFromFormat('Ymd-His', $item->getCreationTimeStamp()))
            ->setErrorCode($item->getErrorCode())
            ->setErrorMessage($item->getErrorDefinition());

        return $packageInfo;
    }

    /**
     * @param string $referenceNumber
     *
     * @throws \Exception
     *
     * @return PackageInfo
     */
    public function getPackageInfoByReferenceNumber(string $referenceNumber): PackageInfo
    {
        $service = $this->initQueryService();
        $resultWebService = $service->GetPackageInfoByReferance_V1(
            new GetPackageInfoByReferance_V1(
                $this->getSessionId(),
                1,
                $referenceNumber
            )
        );
        $response = [];
        foreach ($resultWebService->getGetPackageInfoByReferance_V1Result() as $item) {
            if ($item instanceof PackageInformation) {
                $packageInfo = $this->populatePackageInfoFromItem($item);
                $response[$packageInfo->getTrackingNumber()] = $packageInfo;
            }
        }

        return current($response);
    }

    /*
     * Shipment Status
     */

    /**
     * @param string $trackingNumber
     *
     * @throws \Teknomavi\Kargo\Exception\InvalidParameterValue
     *
     * @return ShipmentStatus
     */
    public function getShipmentStatusByTrackingNumber(string $trackingNumber): ShipmentStatus
    {
        $response = $this->getShipmentStatusByTrackingNumberList([$trackingNumber]);
        if (count($response)) {
            return current($response);
        }

        return $this->shipmentStatusNotFound()->setTrackingNumber($trackingNumber);
    }

    /**
     * @param string[] $list
     *
     * @throws \Teknomavi\Kargo\Exception\InvalidParameterValue
     *
     * @return ShipmentStatus[]
     */
    public function getShipmentStatusByTrackingNumberList(array $list): array
    {
        $service = $this->initQueryService();
        /** @var ReferansTypes $rfrType */
        $rfrType = ReferansTypes::WAYBILL_TYPE;
        $referenceList = new ParamReferansList($rfrType);
        $arrayOfString = new ArrayOfString();
        $arrayOfString->setString(array_unique($list));
        $referenceList->setReferansList($arrayOfString);
        /** @var TransactionTypes $trnType */
        $trnType = TransactionTypes::LAST_TRANSACTIONS;
        $resultWebService = $service->GetTransactionsByList_V1(
            new GetTransactionsByList_V1(
                $this->getSessionId(),
                1,
                $referenceList,
                $trnType
            )
        );
        $response = [];
        foreach ((array) $resultWebService->getGetTransactionsByList_V1Result()->getPackageTransactionwithDeliveryDetail() as $item) {
            if ($item instanceof PackageTransactionwithDeliveryDetail) {
                $shipmentStatus = $this->populateShipmentStatusFromItem($item);
                $response[$shipmentStatus->getTrackingNumber()] = $shipmentStatus;
            }
        }

        return $response;
    }

    /**
     * @param PackageTransactionwithDeliveryDetail|PackageTransaction $item
     *
     * @throws \Teknomavi\Kargo\Exception\InvalidParameterValue
     *
     * @return ShipmentStatus
     */
    private function populateShipmentStatusFromItem($item): ShipmentStatus
    {
        $shipmentStatus = new ShipmentStatus();
        $shipmentStatus->setTrackingNumber($item->getTrackingNumber())
            ->setStatusCode($this->mapStatus($item->getStatusCode()))
            ->setOriginalStatus($item->getStatusCode())
            ->setStatusDetails($item->getProcessDescription2())
            ->setUpdateTime(\DateTime::createFromFormat('Ymd-His', $item->getProcessTimeStamp()))
            ->setErrorCode($item->getErrorCode())
            ->setErrorMessage($item->getErrorDefinition());
        if ($item instanceof PackageTransactionwithDeliveryDetail) {
            $shipmentStatus->setReferenceNumber($item->getCustomerReferanceNumber());
        }

        return $shipmentStatus;
    }

    /**
     * @param string $referenceNumber
     *
     * @throws \Exception
     *
     * @return ShipmentStatus
     */
    public function getShipmentStatusByReferenceNumber(string $referenceNumber): ShipmentStatus
    {
        $response = $this->getShipmentStatusByReferenceNumberList([$referenceNumber]);
        if (count($response)) {
            return current($response);
        }

        return $this->shipmentStatusNotFound()->setReferenceNumber($referenceNumber);
    }

    /**
     * @param string[] $list
     *
     * @throws \Exception
     *
     * @return ShipmentStatus[]
     */
    public function getShipmentStatusByReferenceNumberList(array $list): array
    {
        $service = $this->initQueryService();
        /** @var ReferansTypes $rfrType */
        $rfrType = ReferansTypes::CUSTOMERREFERANS_TYPE;
        $referenceList = new ParamReferansList($rfrType);
        $arrayOfString = new ArrayOfString();
        $arrayOfString->setString(array_unique($list));
        $referenceList->setReferansList($arrayOfString);
        /** @var TransactionTypes $trnType */
        $trnType = TransactionTypes::LAST_TRANSACTIONS;
        $resultWebService = $service->GetTransactionsByList_V1(
            new GetTransactionsByList_V1(
                $this->getSessionId(),
                1,
                $referenceList,
                $trnType
            )
        );
        $response = [];
        foreach ((array) $resultWebService->getGetTransactionsByList_V1Result()->getPackageTransactionwithDeliveryDetail() as $item) {
            if ($item instanceof PackageTransactionwithDeliveryDetail) {
                $shipmentStatus = $this->populateShipmentStatusFromItem($item);
                $response[$shipmentStatus->getReferenceNumber()] = $shipmentStatus;
            }
        }

        return $response;
    }

    /**
     * @param \DateTime $date
     *
     * @throws \Exception
     *
     * @return ShipmentStatus[]
     */
    public function getShipmentStatusByPickupDate(\DateTime $date): array
    {
        $service = $this->initQueryService();
        /** @var TransactionTypes $trnType */
        $trnType = TransactionTypes::LAST_TRANSACTIONS;
        $resultWebService = $service->GetTransactionsByPackagePickupDate_V1(
            new GetTransactionsByPackagePickupDate_V1(
                $this->getSessionId(),
                1,
                $date->format('Ymd'),
                $date->format('Ymd'),
                $trnType
            )
        );
        $response = [];
        foreach ($resultWebService->getGetTransactionsByPackagePickupDate_V1Result() as $item) {
            if ($item instanceof PackageTransactionwithDeliveryDetail) {
                $shipmentStatus = $this->populateShipmentStatusFromItem($item);
                $response[$shipmentStatus->getTrackingNumber()] = $shipmentStatus;
            }
        }

        return $response;
    }

    /**
     * @param \DateTime $date
     *
     * @throws MethodNotSupported
     *
     * @return array|ShipmentStatus[]
     */
    public function getShipmentStatusByDeliveryDate(\DateTime $date): array
    {
        throw new MethodNotSupported();
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'customerCode' => '',
            'username'     => '',
            'password'     => '',
        ]);
    }
}
