<?php 
/**
 * @packageName: pttws
 * @packageDescription: ptt cargo api
 * @packageVersion: 1.0.2.1
 * @packageAuthor: trfoxs
 * @packageLicense: MIT License
 * @packageUrl: MIT License
 * 
 * @link https://github.com/trfoxs/pttws-kargo-api
 * 
 *  Copyright (c) 2014-2023 ptt\pttcargoapi
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 *  
 * 
 */
 namespace ptt\pttcargoapi;
 class pttws {
	/**
	 * private $customer, $password
	 * @var string
	 */
	private $customer, $password;
	
	/**
	 * public
	 * @var strign
	 */
	public  $method = 'test', $aAdres, $agirlik = 1, $aliciAdi, $aliciIlAdi, $aliciIlceAdi,
			$aliciSms, $referenceNo, $boy = 1, $deger_ucreti = 0, $desi = 1, $ekhizmet = '', $en = 1, 
			$odemesekli = '', $odeme_sart_ucreti = 0, $yukseklik = 1, $aliciEmail, $rezerve1 = '', $barkodNo = 0, 
			$dosyaAdi, $kullanici = 'PttWs', $gonderiTip = 'NORMAL', $gonderiTur = 'KARGO', $gonderici_adi = '', $gonderici_adres = '', $gonderici_email = '', $gonderici_il = '', $gonderici_ilce = '', $gonderici_posta = '', $gonderici_telefon = '', $gonderici_ulke_id = '052';
			
	/**
	 * private
	 * @var array
	 */
	private $items = [];
	
	private $error = false;
	
	public function __construct($customer = null, $password = null){
		$this->customer = $customer;
		$this->password = $password;
		
		if (empty($this->customer) && empty($this->password)) {
			return 'ptt müşteri numarası ve şifre boş olamaz!';
		}
		
		//print_r($this);
	}
	
	/**
	 * public
	 * @var array
	 */
	public function getError() {
		return $this->error;
	}
	
	/**
	 * public barcodeDelete()
	 * require $this barkodNo,dosyaAdi,customer,password
	 * @var strign
	 */
	public function barcodeDelete(){
		try {
			$soap = $this->getWs('delete');

			$data = $soap->barkodVeriSil([
				'inpDelete' => [
					'barcode' => $this->barkodNo,
					'dosyaAdi' => $this->dosyaAdi,
					'musteriId' => $this->customer,
					'sifre' => $this->password,
				]
			]);

			if(isset($data->return)){
				return (array)$data->return;
			}else{
				return false;
			}

		}catch ( \SoapFault $fault){
			$this->error = $fault;
		}
	}
	
	/**
	 * public refcodeDelete()
	 * require $this referenceNo,dosyaAdi,customer,password
	 * @var strign
	 */
	public function refcodeDelete(){
		try {
			$soap = $this->getWs('delete');

			$data = $soap->referansVeriSil([
				'inpRefDelete' => [
					'referansNo' => $this->referenceNo,
					'dosyaAdi' => $this->dosyaAdi,
					'musteriId' => $this->customer,
					'sifre' => $this->password,
				]
			]);

			if(isset($data->return)){
				return (array)$data->return;
			}else{
				return false;
			}

		}catch ( \SoapFault $fault){
			$this->error = $fault;
		}
	}
	
	/**
	 * public insert()
	 * require $this->items
	 * @var strign & array
	 */
	public function insert(){
		if (is_null($this->items)) {
			return 'lütfen veri gönderin!';
		}
		
		if (!preg_match('/^[0-9]{10,10}$/i',$this->aliciSms)) {
			return 'gsm numarası 10 haneli sayısal olmalı ve başında (0) olmamalıdır!';
		}
		
		array_push($this->items, [
			'aAdres' => $this->aAdres,
			'agirlik' => $this->agirlik,
			'aliciAdi' => $this->aliciAdi,
			'aliciIlAdi' => $this->aliciIlAdi,
			'aliciIlceAdi' => $this->aliciIlceAdi,
			'aliciSms' => ($this->aliciSms)?$this->aliciSms:'',
			'aliciEmail' => ($this->aliciEmail)?$this->aliciEmail:'',
			'barkodNo' => $this->barkodNo,
			'boy' => $this->boy,
			'deger_ucreti' => $this->deger_ucreti,
			'desi' => $this->desi,
			'ekhizmet' => $this->ekhizmet,
			'en' => $this->en,
			'musteriReferansNo' => ($this->referenceNo)?$this->referenceNo:0,
			'odemesekli' => $this->odemesekli,
			'odeme_sart_ucreti' => $this->odeme_sart_ucreti,
			'rezerve1' => $this->rezerve1,
			'yukseklik' => $this->yukseklik,
			'gondericibilgi' => [
				'gonderici_adi' => $this->gonderici_adi, 
				'gonderici_adresi' => $this->gonderici_adres, 
				'gonderici_email' => $this->gonderici_email, 
				'gonderici_il_ad' => $this->gonderici_il, 
				'gonderici_ilce_ad' => $this->gonderici_ilce, 
				'gonderici_posta_kodu' => $this->gonderici_posta, 
				'gonderici_telefonu' => $this->gonderici_telefon, 
				'gonderici_sms' => $this->gonderici_telefon, 
				'gonderici_ulke_id' => $this->gonderici_ulke_id
			]
		]);
		
		try {
			$soap = $this->getWs('insert');

			$data = $soap->kabulEkle2([
				'input' => [
					'dosyaAdi' => $this->dosyaAdi,
					'gonderiTip' => $this->gonderiTip,
					'gonderiTur' => $this->gonderiTur,
					'kullanici' => $this->kullanici,
					'musteriId' => $this->customer,
					'sifre' => $this->password,
					'dongu' => $this->items
				]
			]);

			if(isset($data->return)){
				return (array)$data->return;
			}else{
				return false;
			}

		}catch ( \SoapFault $fault){
			$this->error = $fault;
		}
	}

	/**
	 * public getBarcode($barcode)
	 * require $barcode
	 * @var strign
	 */
	public function getBarcode($barcode) {
		try {
			$soap = $this->getWs('list');

			$data = $soap->barkodSorgu([
				'input' => [
					'musteri_no' => $this->customer,
					'sifre' => $this->password,
					'barkod' => $barcode,
				]
			]);

			if(isset($data->return)){
				return (array)$data->return;
			}else{
				return false;
			}

		}catch ( \SoapFault $fault){
			$this->error = $fault;
		}
	}
	
	/**
	 * public getRefcode($refcode)
	 * require $refcode
	 * @var strign
	 */
	public function getRefcode($refcode) {
		try {
			$soap = $this->getWs('follow');

			$data = $soap->gonderisorgu_referansno([
				'input' => [
					'kullanici' => $this->customer,
					'sifre' => $this->password,
					'referansNo' => $refcode
				]
			]);

			if(isset($data->return)){
				return (array)$data->return;
			}else{
				return false;
			}

		}catch ( \SoapFault $fault){
			$this->error = $fault;
		}
	}
	
	/**
	 * public getRefcode($date)
	 * require $date
	 * @var strign
	 */
	public function getDate($date) {
		if ( ! $this->validateDate($date) ){
			return 'Geçersiz bir tarih girdiniz...';
		}

		$date = str_replace('-', '', $date);

		try {
			$soap = $this->getWs('list');

			$data = $soap->gonderiHareketIslemTarihiSorgu([
				'input' => [
					'musteri_id' => $this->customer,
					'sifre' => $this->password,
					'son_islem_tarihi' => $date,
				]
			]);

			$collect = [
				'aciklama' => isset($data->return->aciklama) ? $data->return->aciklama : null,
				'barkod_devam' => isset($data->return->barkod_devam) ? $data->return->barkod_devam : null,
				// 'dongu' => [],
				'rcode' => isset($data->return->rcode) ? $data->return->rcode : null,
				'sqlcode' => isset($data->return->sqlcode) ? $data->return->sqlcode : null,
			];


			$items = [];

			if( isset($data->return->dongu) ){
				if( is_array($data->return->dongu) ){
					foreach ($data->return->dongu as $item){
						array_push($items, (array)$item);
					}
				}else{
					array_push($items, (array)$data->return->dongu);
				}
			}

			$collect['dongu'] = $items;

			return $collect;

		}catch ( \SoapFault $fault){
			$this->error = $fault;
		}
	}
	
	/**
	 * public calBarcode($barkodIncrementId)
	 * require (int)$barkodIncrementId
	 * calculate barcode ptt
	 * @var int
	 */
	public function calBarcode($barkodIncrementId) {
		$carpanSplit = [1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3];
		$barkodSplit = str_split($barkodIncrementId);

		if( count($barkodSplit) != 12 ){
			return false;
		}

		$sum = 0;

		for ($i=0; $i < 12; $i++){
			$sum += $carpanSplit[$i] * $barkodSplit[$i];
		}

		$nearest = (int)ceil($sum / 10) * 10;
		$checkDigit = $nearest - $sum;

		array_push($barkodSplit, $checkDigit);

		return implode('', $barkodSplit);
	}
	
	/**
	 * protected getWs($ws)
	 * require $ws (insert,delete,list,follow)
	 * soap client
	 * @var strign
	 */
	protected function getWs($ws = null) {
		$run = '';
		
		$methods = array(
			'insert' => 'PttVeriYukleme', 
			'delete' => 'PttVeriYukleme', 
			'list' => 'GonderiHareketV2', 
			'follow' => 'GonderiTakipV2' 
		);
		
		if ($methods[$ws]) {
			$run = $methods[$ws];
		}else{
			return 'Lütfen method gönderiniz. (insert,delete,list,follow)';
		}
		
		if ($this->method == 'test') {
			return new \SoapClient("https://pttws.ptt.gov.tr/{$run}Test/services/Sorgu?wsdl");
		}else if ($this->method == 'live'){
			return new \SoapClient("https://pttws.ptt.gov.tr/{$run}/services/Sorgu?wsdl");
		}
		
		return false;
		
	}
	
	/**
	 * protected validateDate($date)
	 * require $date
	 * @var strign
	 */
	protected function validateDate($date, $format = 'Y-m-d'){
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
 }