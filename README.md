# Katini
Tool voor contactbeheer gericht op Nederlandse zendelingen en hun Thuisfrontteams / Thuisfrontcommitee's (TFTs en TFCs).

De gebruikersinterface van Katini is in het Nederlands, de achterliggende code gebruikt de Engelse taal voor zowel code als commentaar.

> [!IMPORTANT]
> Katini is nog volop in ontwikkeling en zeker nog niet af.

## Installatie
Katini is gebouwd op het [CodeIgniter4 Framework](https://www.codeigniter.com) en maakt gebruik van [Composer packages](https://www.getcomposer.com). Voor systeemvereisten zie [CodeIgniter4 Server Requirements](https://codeigniter.com/user_guide/intro/requirements.html).

1. Haal het Katini GIT repository binnen via `git clone https://github.com/christianberkman/katini.git`
2. Installeer composer packages via `composer install`
3. Zet de webserver op en pas de configuratie aan, zie [Running Your App](https://codeigniter.com/user_guide/installation/running.html), [Configuration](https://codeigniter.com/user_guide/general/configuration.html) en [Deployment](https://codeigniter.com/user_guide/installation/deployment.html). Het gebruik van een `.env` file is aan te raden zodat de configuratie buiten de GIT repository blijft en niet verloren gaat bij een update.
4. Maak verbinding met een database. SQLite3 is aanbevolen en vereist geen aparte server. Zie [Database Configuration](https://codeigniter.com/user_guide/database/configuration.html).
5. Voer het setup script uit om Katini op te zetten: `php spark katini:setup`. Dit script voert de database migraties uit, en stelt een gebruiker voor de beheerder in.
6. Stel de cronjob in voor het verwerken van periodieke donaties, zie [Setting the Cron Job](https://tasks.codeigniter.com/configuration/#setting-the-cron-job)

## Overzicht
Een korte beschrijving van de onderdelen

### Supporters
Een supporter is een individu of een groep (familie, bedrijf) die de zendeling onderstend. Voor elke supporter kan contactinformatie en een notitie opgeslagen worden.

### Kringen
Een kring is een groep van supporters

### Donaties
Een donatie anoniem zijn of gekoppeld worden aan een supporter. Een donatie kan eenmalig zijn of periodiek, met een interval tussen de 1 en 2 maanden. Via een Cron Job worden periodieke donaties automatisch toegevoegd.

## Geplande functies
* Adreslijsten
* Registratie uitgaven
* Doelen voor donaties en uitgaven
* Contactmomenten
* Simpel forum voor samenwerking
* Uitgebreidere statistieken en dashboards



