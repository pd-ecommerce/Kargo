<?php


class PttSoapClient
{
    private $customerId;
    private $username;
    private $password;

    private $testMode;

    private $barcodeStart;
    private $barcodeEnd;

    private $url;

    private $fileName;
    private $shipmentKind;
    private $shipmentType;
    private $paymentType;

    public function __construct($options)
    {
        $this->customerId = $options['customerId'];
        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->barcodeStart = $options['barcodeStart'];
        $this->barcodeEnd = $options['barcodeEnd'];
        $this->testMode = $options['testMode'];

        if ((int)$this->testMode) {
            $this->url = "https://pttws.ptt.gov.tr/PttVeriYuklemeTest/services/Sorgu?wsdl";
        } else {
            $this->url = "https://pttws.ptt.gov.tr/PttVeriYukleme/services/Sorgu?wsdl";
        }

        $this->fileName  = date('Ymd-His-').uniqid();
        $this->shipmentKind = "KARGO";
        $this->shipmentType = "NORMAL";
        $this->paymentType = "UA";
    }

    public function createShipment(
        $receiverCustomerName,
        $receiverAddress,
        $cityName,
        $townName,
        $receiverPhone1
    )
    {
        $client = new \SoapClient($this->url);

        return $client->kabulEkle2([
            'input' => [
                'dosyaAdi' => $this->fileName,
                'gonderiTip' => $this->shipmentType,
                'gonderiTur' => $this->shipmentKind,
                'kullanici' => $this->username,
                'musteriId' => $this->customerId,
                'sifre' => $this->password,
                'dongu' => [
                    'aliciAdi' => $receiverCustomerName,
                    'aAdres' => $receiverAddress,
                    'aliciIlAdi' => $cityName,
                    'aliciIlceAdi' => $townName,
                    'aliciSms' => $receiverPhone1,
                    'barkodNo' => $this->generateBarcode(),
                    'odemesekli' => $this->paymentType
                ]
            ]
        ]);
    }

    public function queryShipment($trackingNumber)
    {
        try {
            if ((int)$this->testMode) {
                $client = new SoapClient('https://pttws.ptt.gov.tr/GonderiHareketV2/services/Sorgu?wsdl');
            } else {
                $client = new SoapClient('https://pttws.ptt.gov.tr/GonderiHareketV2/services/Sorgu?wsdl');
            }

            $data = $client->barkodSorgu([
                'input' => [
                    'musteri_no' => $this->customerId,
                    'sifre' => $this->password,
                    'barkod' => $trackingNumber,
                ]
            ]);

            if(isset($data->return)){
                return (array)$data->return;
            }else{
                return false;
            }

        }catch ( \SoapFault $fault){
            return $fault;
        }
    }

    function generateBarcode()
    {
        // Ptt tarafından size verilen barkod aralığı
        $code = mt_rand($this->barcodeStart, $this->barcodeEnd);
        $codeArr = str_split($code);
        $codeTotal = array();
        foreach ($codeArr as $key => $codeVal) {
            if($key % 2 == 0) {
                $codeTotal[] = $codeVal * 1;
            } else {
                $codeTotal[] = $codeVal * 3;
            }
        }
        $codeSum = array_sum($codeTotal);
        $newCode = $code.$codeSum % 10;
        return $newCode;
    }

    public function getFileName()
    {
        return $this->fileName;
    }
}