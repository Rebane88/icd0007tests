## ICD0007 Projekti testid

### Testide käivitamine
`php testideFailiNimi.php`

### Veakoodide seletused

`N01` Probleem võrguühendusega. Üks võimalus on see, et server ei käi oodatud aadressil.

`N02` Server vastas veaga. See juhtub siis, kui küsitud faili ei leita või Php skript lõpetab veaga.

`C01` Üldine viga, mille puhul peaks veateade ise piisav olema.

`C02` Võrreldavad väärtused ei ole võrdsed. Lahendamiseks peaks testist välja lugema, millist väärtust test ootab ja miks ta seda ootab. Kui see on selge, siis jääb vaid üle selgeks teha, miks teie programm teisiti käitub.

`C03` Otsitavad sõne ei leita otsitavast tekstist. Lahendus on sama, mis `C02` puhul.

`H01` Viga programmi poolt väljastatavas Html-is. Kui pöörduti staatilise lehe poole, siis pole selle lehe Html kood korrektne. Võimalik, et mõni _tag_ on kinni panemata või kuskil on mingi sümbol, mis selles kohas ei peaks olema. Koos veateatega väljastatakse ka info vea asukoha kohta. Kui sellest jääb väheks, siis võib abi olla https://validator.w3.org/ lehel olevast validaatorist. Kui Html-i genereeris Php skript, siis on vea otsimine tülikam. Vea otsimiseks peaksite kõigepealt väljundit nägema. Selleks võiks teha brauseris samad sammud läbi, mida test teeb ja õiges kohas "View page source" valida.

`H02` Lingi _href_ atribuut või vormi _action_ atribuut sisaldab keelatud sümboleid. Veebi aadressis ei tohi olla näiteks tühikuid. Probleemi lahendamiseks peaksite probleemsed sümbolid eemaldama. Üks võimalus nendest sümbolitest lahti saada, on info kodeerimine urlencode() funktsiooniga.

`H03` Test ootas, et näiteks lingile vajutades navigeeriti kindlale aadressile aga tegelikult see nii ei olnud. Probleemi lahendamiseks peaks selgeks tegema, millist käitumist test ootab. Kui see on selge, siis jääb vaid üle selgeks teha, miks teie programm teisiti käitub.

`H04` Test otsib, praeguselt lehe tekstist kindlat sõne. Teksti all on mõeldud seda teksti, mida kasutaja brauserist näeb, mitte Html lähtekoodi. Praegune leht on see, kuhu testi abil on navigeeritud. Kui otsitud sõne ei leita, siis on võimalus, et see on pisut teisiti kirjutatud või puudub üldse. Lisaks on võimalus, et teie programm ei väljastanud õiget lehtegi. Näiteks test ootas, et nimekirja lehel on kindel väärtus aga teie programm näitas hoopis vormi lehte. Probleemi kindlakstegemisel võiks abi olla funktsioonist printPageText(). Kui kirjutate testis vea põhjustanud assertPageContainsText() väljakutse ette meetodi väljakutse printPageText(), siis on väljundist näha, mis teksti seest test oodatud sõne otsib.

`H05` Test otsib, praeguse lehe Html lähtekoodist kindlat sõne. Probleemi lahendamine on analoogiline `H04` lahendamisega. Praeguse lehe lähtekoodi väljastab meetod printPageSource().

`W03` Test ootab, et praegusel lehel on link millel on _id_ atribuut testis määratud väärtusega.
Vea tuvastamisel võib abiks olla meetod printPageSource(), mille peaks kirjutama vahetult viga tekitava lause ette.

`W04` Test ootab, et praegusel lehel on link mille sisuks on tekst testis määratud väärtusega.
Vea tuvastamisel võib abiks olla meetod printPageSource(), mille peaks kirjutama vahetult viga tekitava lause ette.

`G01` Ootamatu viga. Test ei ole sellise olukorraga arvestanud. Siin on võimalus, et teie programm on korrektne ja viga on testis.
