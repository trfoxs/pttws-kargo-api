# PTT KARGO API EXTENDED
![](https://img.shields.io/badge/Ver.-1.0.1-dark) ![](https://img.shields.io/badge/Author-trfoxs-blue) ![](https://img.shields.io/badge/profile-semihbtr-green?logo=linkedin&style=flat-square) ![](https://shields.io/badge/license-MIT-informational) ![](https://img.shields.io/badge/english-red) ![](https://img.shields.io/badge/turkish-red) @ahmeti :+1: teşekkürler

pttws soap client php api entegrasyon

### Gereksinimler
- PHP 5.6.x ve üzeri !
- SoapClient extension_loaded('soap') kurulu ve aktif olmalıdır.

## Kurulum
- Github ile verileri indirin
- require('ptt\pttcargoapi.php'); sayfanıza ekleyin.

### komutlar
- [x] insert (PttVeriYukleme)
  - [ ] kabulEkle
  - [x] kabulEkle2
  - [ ] siparisIstekEkle2
  - [ ] kabulEkleParcaliBarkod
  - [ ] InputParcaliBarkodDongu
- [x] delete (PttVeriYukleme)
  - [x] referansVeriSil
  - [x] barkodVeriSil
- [x] list (GonderiHareketV2)
  - [x] barkodSorgu
  - [x] gonderiHareketIslemTarihiSorgu
  - [ ] gonderiHareketBarkodSorgu
  - [ ] gonderiKabulSorgula
  - [ ] gonderiTalimatSorgula
- [x] follow (GonderiTakipV2)
    - [ ] gonderisorgu
    - [ ] gonderisorgu2
    - [x] gonderisorgu_referansno 
    - [ ] getcity
    - [ ] getdistrict
    - [ ] getdroppointinfo
    - [ ] shipmentinquiryen
    - [ ] referanssorgu (kaldırılmıştır|deprecated)
    
## KULLANIM
api dosyamız aktarıkır ve fonksiyon çağırılır.
```php
require('ptt/pttcargoapi.php');
$ptt = new \ptt\pttcargoapi\pttws('customerId','customerPassword'); // ptt tarafından verilen kodlar
```
### INSERT | VERİYÜKLEME

```php
$ptt->method = 'test'; // test | live
$ptt->dosyaAdi = 'TEST-'.date('Ymd-His-').uniqid(); // tarih ve saat uniqid
$ptt->referenceNo = '1234567890'; // müşteri referans no, rand() kullanılabilir
$ptt->barkodNo = $ptt->callBarcode('123456789122'); // 12 haneli Ptt tarafından size temin edilen barkod aralığı varsa otomatik hesaplar
$ptt->aAdres = 'deneme mah. deneme sok. no 1'; // alici adres
$ptt->aliciAdi = 'Hasan ALİ';
$ptt->aliciIlAdi = 'istanbul';
$ptt->aliciIlceAdi = 'kadıköy';
$ptt->aliciSms = '5901110022'; // 10 haneli numeric
$ptt->aliciEmail = 'hasanali@example.com'; // 10 haneli numeric
$ptt->agirlik = 1; // ağırlık gram cinsinden
$ptt->boy = 1; // kargo boyu
$ptt->en = 1; // kargo eni
$ptt->yukseklik = 1; // kargo eni
$ptt->desi = $ptt->en*$ptt->boy*$ptt->yukseklik/3000; // Gönderinin en*boy*yükseklik/3000 formülü ile hesaplanır. yoksa 1 yazınız
$ptt->deger_ucreti = 0; // sigorta bedeli eklemek için
$ptt->rezerve1 = '5287402'; // postaçeki varsa gönderici
$ptt->ekhizmet = ''; // kılavuza bakınız
$ptt->odemesekli = ''; // Mahsup=MH, Nakit=N, Ücreti alıcıdan=UA, Kapıda Ödeme=N1
$ptt->odeme_sart_ucreti= 200; // Gönderi teslim edilirken alıcıdan ürün fiyatı temin edilecekse gönderilir yoksa 0 yazınız

$result = $ptt->insert(); // veriler gönderildi.

var_dump($result);
C:\wamp\www\ptttest\index.php:374:
array (size=3)
  'aciklama' => string 'BASARILI' (length=8)
  'dongu' => 
    object(stdClass)[5]
      public 'barkod' => string '' (length=0)
      public 'donguAciklama' => string 'https://pttws.ptt.gov.tr/ReferansSorgu/faces/referansSorgu.xhtml?musteri_no=785675890&referans=1234567890&guid=vpPUdQ933OhUTYyM0lSYzw' (length=132)
      public 'donguHataKodu' => int 1
      public 'donguSonuc' => boolean true
  'hataKodu' => int 1
```
----
### DELETE | BARKOD VE REFERANS SİLME
```php
$ptt->dosyaAdi = 'TEST-test123'; önceden kaydedilen dosya adı buraya
$ptt->barkodNo = '123456789122'; // barkod numarası
$ptt->referenceNo = '123456789'; // referans numarası

$result = $ptt->barcodeDelete();
$result = $ptt->refcodeDelete();

var_dump($result);
C:\wamp\www\ptttest\index.php:374:
array (size=2)
  'aciklama' => string 'Barkod veya dosyaadi hatali' (length=27)
  'hataKodu' => int -4
```
----
### LIST | gönderi barkod & referans sorgulama
```php
$result = $ptt->getRefcode('1234567890');
$result = $ptt->getBarcode('KPveyanormalbarkod');

var_dump($result);

C:\wamp\www\ptttest\index.php:374:
array (size=2)
  'aciklama' => string 'Barkod veya dosyaadi hatali' (length=27)
  'hataKodu' => int -4

```
----
