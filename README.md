# Magic: the Cards

Deze applicatie is ontwikkeld voor het vak: "Webtechnologie 2". De applicatie is bedoeld als 
een beheersysteem voor de speelkaarten van het spel: "Magic: the Gathering". De achterliggende 
bedoeling is om een zelf-gemaakte PHP-framework toe te passen in een Proof of Concept.

## Installeren en opzetten van de applicatie

### SQLite setup
De applicatie maakt gebruik van een SQLite-database. Deze is bij de repository inbegrepen. 
Echter is het wel noodzakelijk dat de "pdo_sqlite" extensie is ingeschakeld in de php configuratie(php.ini)

### Composer installeren
Voor autoloading wordt er gebruik gemaakt van Composer. Hiervoor is het noodzakelijk dat Composer is geconfigureerd
op het systeem. [Meer informatie hier](https://getcomposer.org/doc/00-intro.md)

Als Composer correct werkt, kan moet de autoloading geladen worden. Hiervoor moet het volgende commando worden
uitgevoerd in de root directory van het project.

```bash
composer install
```
### Webserver opzetten
De applicatie kan uitgevoerd worden met de ingebouwde webserver van PHP. Deze kan opgestart worden
met het volgende commando in de root directory van het project.

```bash
php -S localhost:8000
```
Hierna is de applicatie beschikbaar op [http://localhost:8000](http://localhost:8000)

## Gebruik van de applicatie

### Authenticatie
De applicatie kan alleen gebruikt worden als een gebruiker is ingelogd. Een standaardgebruiker kan aangemaakt
worden via [http://localhost:8000/register]. Ook kan gebruikt gemaakt worden van de vooraf ingestelde gebruikers
voor alle types van gebruikers. Hieronder staan de benodigde inloggegevens:

| Type gebruiker | Emailadres      | Wachtwoord |
|----------------|-----------------|------------|
| Standaard      | regular@regular | regular    |
| Premium        | premium@premium | premium    |
| Admin          | admin@admin     | admin      |

### Routes
Van het totale aantal routes van de applicatie zijn een paar bedoeld om te navigeren door de applicatie.
De applicatie begint bij [http://localhost:8000](http://localhost:8000). Als de gebruiker niet is ingelogd,
wordt deze verwezen naar de inlogpagina op [http://localhost:8000/login](http://localhost:8000/login). Hier heeft de
gebruiker ook de mogelijkheid om te navigeren naar de registratiepagina op [http://localhost:8000/register](http://localhost:8000/register).
Premiumgebruikers hebben toegang tot de deckpagina op [http://localhost:8000/decks](http://localhost:8000/decks), en administators
hebben toegang tot het administatordashboard op [http://localhost:8000/admin](http://localhost:8000/admin)