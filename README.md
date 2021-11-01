# Laravel kiinduó projekt

## Szerver előkészítése

A `https://github.com/rcsnjszg/laravel-alap.git` egy olyan alap projektet tartalmaz, amiben már benne tartalmaz egy teljes webszervert, továbbá a `www` mappában a `https://github.com/laravel/laravel` oldalon található laravel egy változatát.

A tároló klónozásával hozzunk létre egy új projektet.
A `projekt_neve` helyére illesszük be, hogy melyik mappában szeretnénl ezt megtenni.

```bash
git clone https://github.com/rcsnjszg/laravel-alap.git projekt_neve
```

Amennyiben nem lenne git a gépünkön telepítve, az előbbi műveletet docker segítségével is megtehetjük:

```bash
docker run -it --rm -v $(pwd):/git alpine/git clone \
    https://github.com/rcsnjszg/laravel-alap.git projekt_neve
```

Ahogy a Laravel projekt esetén, így itt is szükségünk lesz egy `.env` fájlra,
amit a `.env.example` másolásával hozhatjuk létre a legegyszerűbben.
Ezt a projektünk `www` mappájában tegyük meg, hiszen itt található a laravel kódja,
így itt is keresi.

```bash
cd projekt_neve/www
cp .env.example .env
```

Mivel a php nem egy kész image lesz, hanem mi magunk építjük fel, továbbá kiegészítéseket is telepítünk hozzá,
így a `docker/php/Dockerfile` "recept" alapján kell felépíteni:

```bash
docker-compose build
```

*Első futtatáskor sok időt vehet igénybe. Amennyiben a Dockerfile-t nem módosítjük legközelebb ezen gyorsan túlléphetünk*

Innentől a `docker-compose up` paranccsal indíthatjuk a szerverünket. A `-d` kapcsoló segítségével `detached`  módbín indítjuk, aminek eredményeképp a konténerek kimenete nem kerül a képernyőnkre. Ha szeretnénk visszakeresni, akkor a log fájlból visszanyerhetjük.

```bash
docker-compose up -d
```

\pagebreak

## Laravel projekt indítása konténeren kívülről

Mivel a laravel felhasznál rengeteg előre megírt osztályt, így eszeket a `composer` segítségével telepíteni kell.
Mivel ez egy php nyelven írt alkalmazás, így ezt a php konténerünkben kell, hogy futtassuk. Mivel a `Dockerfile` tartalmazza, így magát a composert nem szükséges letölteni/telepíteni.

```bash
docker-compose exec php composer install
```

A laravel működéséhez szükséges egy egyedi kulcs (API Key) generálása,
amit a laravelhez csomagolt konzolos php szkript,
az `artisan` megfelelő paraméterezésével `key:generate` hozhatjuk létre.

```bash
docker-compose exec php php artisan key:generate
```

A `--show` segítségével megjeleníthetjük rögtön a generált kulcsot.

```bash
docker-compose exec php php artisan key:generate --show
```

Ekkor a `.env` fájlban az `APP_KEY` értékét beállítja.
Ennek eredményét az alábbi szripttel ellenőrízhatjük.

```bash
docker-compose exec php cat .env | grep ^APP_KEY
```

## Laravel parancsok futtatása a konténerben.

Amennyiben a `php` konténeren belül adunk ki parancsokat,
úgy az elejéről a `docker-compose exec php` elhagyható.

Ehhez elsőként csatlakozzunk a a konténerhez.
Mivel a `docker-compose` segítségével indítottuk, így most ia azt használhatjuk.

Figyelem! A `www` mappából futtassuk.

A `docker-compose exec kontener_neve futtatandó_program paraméterek` sablon alapján tudunk nekiindulni.

Mivel egy shell-t szeretnénk kapni, így egy létező nevét adhatjuk meg.
Elsőként lehet a `/bin/bash` jut eszünkbe, de azt alapból az alpine php nem tartalmazza és nem is telepítettük.
Egy másik lehetőség, amit alapból tartalmaz az a `/bin/sh`, bár akad közöttük különbség.

Egy kis összehasonlítás: https://www.baeldung.com/linux/sh-vs-bash.

A könnyebb kezelhetőség kedvéért a `fish` hozzá lett adva a `Dockerfile`-ban, így érdemes ezt választani.

```bash
docker-compose exec php fish
```

\pagebreak

Amenyiben a php konténerben vagyunk egyszerűen futtathatjuk a szükséges parancsokat.

```bash
composer install
php artisan key:generate --show
cat .env | grep ^APP_KEY
```

## Hibakezelés

**No application encryption key has been specified.**

Amennyiben a kulcs lelett generálva, de ennek ellenére sem működik, akkor a konfigurációs cache-t érdemes újra generálni    .

```bash
php artisan config:cache
```

 - https://stackoverflow.com/questions/53236518/difference-between-php-artisan-configcache-and-php-artisan-cacheclear-in-l

**Valamelyik fájl nem írható**



Amennyiben jogosultság probléma állna fenn az alaáábi értékeket kellene helyesen megadni a `.env` fájlban.

```text
UID=1000
GID=1000
```

**UID értéke:** `id -u`

**GID értéke:** `id -g`


 >  The ability to automatically process UID and GID vars will not be added to compose. Reasoning is solidly explained on github (https://github.com/compose-spec/compose-spec/issues/94) as a potential security hole in a declarative language.

- https://stackoverflow.com/questions/50552970/laravel-docker-the-stream-or-file-var-www-html-storage-logs-laravel-log-co
- https://stackoverflow.com/questions/56844746/how-to-set-uid-and-gid-in-docker-compose
- https://dev.to/acro5piano/specifying-user-and-group-in-docker-i2e


**Hogyan helyes? "`composer`" vagy "`php composer`"?**

A konténerben a composer úgy van bekonfigurálva, hogy tudja sajátmagáról, hogy ő egy PHP script. Továbbá a `PATH`-ben szerepel a `php` elérési útvonala.

Ezeknek köszönhetően elhagyható a `php` a szkript futtatása előtt. Máshol szükséges kirakni!

\pagebreak

**Hogyan helyes? "`artisan`",  "`php artisan`" vagy "`./artisan`"?**

A composer-hez hasonlóan az artisan is php-ban megírt konzolos script lesz.

Meghívható a `php` közvetlen megadásával, de ez akár el is hahyható. ugyanakkor a composerrel ellentétben, mivel nem globálisan telepített szkript, így az `artisan` nem, de a `./artisan` működőképes.
