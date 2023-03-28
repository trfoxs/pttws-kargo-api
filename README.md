# PTT KARGO API EXTENDED
![](https://img.shields.io/badge/Ver.-1.0.2-dark) ![](https://img.shields.io/badge/Author-trfoxs-blue) ![](https://img.shields.io/badge/profile-semihbtr-green?logo=linkedin&style=flat-square) ![](https://shields.io/badge/license-MIT-informational) ![](https://img.shields.io/badge/english-red) ![](https://img.shields.io/badge/turkish-red) @ahmeti :+1: teşekkürler

pttws soap client php api entegrasyon

### Gereksinimler
- PHP 5.6.x ve üzeri !
- SoapClient extension_loaded('soap') kurulu ve aktif olmalıdır.

### Kurulum
- Github ile verileri indirin
- require('ptt\pttcargoapi.php'); sayfanıza ekleyin.

### Ek Hizmet Kodları
```
AA - ADRESTEN ALMA
ST - ŞEHİR İÇİ TESLİM
DK - DEĞER KONULMUŞ
OS - ÖDEME ŞARTLI
AH - ALMA HABERLİ
AK - ALICININ KENDİNE TESLİM
TA - TELEFONLA BİLGİLENDİRME
KT - KONTROLLU TESLIM
OU - ÖZEL ULAK
UA - ÜCRETİ ALICIDAN TAHSİL
GD - GİDİŞ-DÖNÜŞ
SV - SERVİS
OS - ÖDEME ŞARTI
RP - RESMİ PUL
UO - ÜCRET ÖDEME MAKİNESİ
VI - KREDİ KARTI
PC - POSTA ÇEKİ HESABI
DN - BARKOD DÖNÜŞLÜ
PI - PTT ISYERINE TESLIM
AT - ADLI TIP
PR - POSTRESTANT
SB - SMS ILE BILGILENDIRME
```

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
    
### KULLANIM
api dosyamız aktarıkır ve fonksiyon çağırılır.
```php
require('ptt/pttcargoapi.php');
$ptt = new \ptt\pttcargoapi\pttws('customerId','customerPassword'); // ptt tarafından verilen kodlar
```
#### INSERT | VERİYÜKLEME

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
$ptt->aliciEmail = 'hasanali@example.com'; // email adresi
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

// gönderici bilgileri
$asd->gonderici_adi = 'Mehmet YURT';
$asd->gonderici_adres = 'alkan mah. yılmaz sok no 1/A';
$asd->gonderici_email = 'mehmet.yurt@example.com';
$asd->gonderici_telefon = '5550001122';
$asd->gonderici_il = 'istanbul';
$asd->gonderici_ilce = 'avcılar';
$asd->gonderici_ulke_id = '052'; // varsayılan 052 sabittir ve isteğe bağlı veridir, lütfen kılavuza bakınız.

$result = $ptt->insert(); // veriler gönderildi.

var_dump($result);
C:\wamp\www\ptttest\index.php:374:
array (size=3)
  'aciklama' => string 'BASARILI' (length=8)
  'dongu' => 
    object(stdClass)[5]
      public 'barkod' => string '' (length=0)
      public 'donguAciklama' => string 'https://pttws.ptt.gov.tr/ReferansSorgu/faces/referansSorgu.xhtml?musteri_no=XXXXXXXXX&referans=XXXXXXXXX&guid=XXXXXXXXX' (length=132)
      public 'donguHataKodu' => int 1
      public 'donguSonuc' => boolean true
  'hataKodu' => int 1
```
----
### DELETE | barkod & referans silme
```php
$ptt->dosyaAdi = 'TEST-test123'; // önceden kaydedilen dosya adı buraya
$ptt->barkodNo = '123456789122'; // barkod numarası
$ptt->referenceNo = '123456789'; // referans numarası

$result = $ptt->barcodeDelete();
$result = $ptt->refcodeDelete();

var_dump($result);
C:\wamp\www\ptttest\index.php:407:
array (size=2)
  'aciklama' => string '1 adet kayit silindi.' (length=21)
  'hataKodu' => int 1
```
----
### LIST - FOLLOW | gönderi barkod & referans sorgulama
```php
$result = $ptt->getRefcode('1234567890');
$result = $ptt->getBarcode('barkod'); // KPxxxxxxxxxx veya barkodno

var_dump($result);
C:\wamp\www\ptttest\index.php:407:
array (size=15)
  'ALICI' => string 'HASAN ALİ' (length=15)
  'BARNO' => string 'KP02XXXXXXXXX' (length=13)
  'DEGKONUCR' => string '0.00 TL' (length=7)
  'EKHIZ' => string ' ' (length=1)
  'GONDEREN' => string 'MEHMET YURT' (length=15)
  'GONUCR' => string '10.50 TL' (length=7)
  'GR' => string '500Gr/0.33D.' (length=12)
  'IMERK' => string 'GENEL MÜDÜRLÜK/GENEL MÜDÜRLÜK' (length=35)
  'ITARIH' => string '20230323' (length=8)
  'ODSARUCR' => string '0.00 TL' (length=7)
  'TESALAN' => string ' ' (length=1)
  'VMERK' => string 'ISTANBUL' (length=8)
  'dongu' => 
    object(stdClass)[5]
      public 'IKODU' => string '1' (length=1)
      public 'IMERK' => string 'GENEL MÜDÜRLÜK/GENEL MÜDÜRLÜK' (length=35)
      public 'ISAAT' => string '15:42:34' (length=8)
      public 'ISLEM' => string 'Kabul Edildi' (length=12)
      public 'ITARIH' => string '23/03/2023' (length=10)
      public 'siraNo' => int 1
  'sonucAciklama' => string 'https://pttws.ptt.gov.tr/Gonderi_Sorgu/faces/index.xhtml?barkod=KP02XXXXXXXXX&barkod_guid=xxxxxxxxxxxxxxx' (length=112)
  'sonucKodu' => int 10

```
----
### LIST | gönderi işlem tarihi sorgulama
```php
$result = $ptt->getDate('2023-03-23');

var_dump($result);
C:\wamp\www\ptttest\index.php:407:
array (size=5)
  'aciklama' => string 'gonderiHareketIslemTarihiSorgu : Islem Tamamlandi.' (length=50)
  'barkod_devam' => string '' (length=0)
  'rcode' => int 1
  'sqlcode' => int 1
  'dongu' => 
    array (size=1)
      0 => 
        array (size=34)
          'agirlik' => string '500' (length=3)
          'barkod_no' => string 'KP02564546802' (length=13)
          'boy' => string '10' (length=2)
          'deger_konulmus_ucret' => string '0.00' (length=4)
          'desi' => string '0.33' (length=4)
          'dosya_adi' => string ' ' (length=1)
          'ek_hizmet' => string ' ' (length=1)
          'en' => string '10' (length=2)
          'gerceklesen_ziyaret_sayisi' => string '1' (length=1)
          'gonderi_durum_aciklama' => string 'Teslim Edildi' (length=13)
          'gonderi_durumu' => string 'TESLIM' (length=6)
          'iade_tarihi' => string 'Iade Tarihi Bulunamadi' (length=22)
          'kabul_il_ad' => string 'ANKARA' (length=6)
          'kabul_merkezi' => string 'GENEL MÜDÜRLÜK' (length=17)
          'kabul_saati' => string '15501770' (length=8)
          'kabul_tarihi' => string '20230323' (length=8)
          'musteri_id' => string 'XXXXXXXXX' (length=9)
          'musteri_referans_no' => string 'XXXXXXXXX' (length=9)
          'odeme_sartli_ucret' => string '0.00' (length=4)
          'posta_ceki' => string '0' (length=1)
          'ptt_teslim_tarihi' => string '20230323' (length=8)
          'rezerve1' => string '' (length=0)
          'rezerve2' => string '' (length=0)
          'rezerve3' => string '' (length=0)
          'rezerve4' => string '' (length=0)
          'rezerve5' => string '' (length=0)
          'siparis_id' => string '0' (length=1)
          'son_islem_il_adi' => string 'ANKARA' (length=6)
          'son_islem_merkez_adi' => string 'GENEL MÜDÜRLÜK' (length=17)
          'son_islem_saati' => string '15523316' (length=8)
          'son_islem_tarihi' => string '20230323' (length=8)
          'talimat_durum' => string '0' (length=1)
          'teslim_alan' => string 'HASAN ALİ' (length=11)
          'yukseklik' => string '10' (length=2)

```
----
