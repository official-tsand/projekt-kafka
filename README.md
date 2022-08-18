# Herzlich Willkommen zum kafka Repository ✒️

### Inhaltsverzeichnis:
1. <a href="#was-ist-kafka">Was ist "kafka"</a>
2. <a href="#was-wird-benötigt">Was wird benötigt?</a>
3. <a href="#was-kommt-als-nächstes">Was kommt als Nächstes?</a>
   * <a href="#herunterladen-des-repositories">Herunterladen des Repositories</a>
   * <a href="#importieren-der-datenbank">Importieren der Datenbank</a>
   * <a href="#konfigurieren-der-datenbankverbindung">Konfigurierung der Datenbankverbindung</a>
   * <a href="#starten-des-servers-und-der-datenbank">Starten des Servers und der Datenbank</a>
4. <a href="#fertig">Fertig</a>
   * <a href="#bedienung">Bedienung</a>
5. <a href="#du-als-programmierer-darfst-dich-auf-tolle-neue-funktionen-freuen">Was wird kommen?</a>

## Was ist "kafka"?

*kafka* ist ein digitales Forum, welches Zitaten berühmter Dichter und Denker ein Zuhause gibt. Sei auch du Teil einer wunderbaren Community und diskutiere fleißig mit!


## Was wird benötigt

Benötigt wird:
* ein (lokaler) Webserver
* eine dazugehörige Datenbank
* PHP

Ich habe das Programmpaket <a href="https://www.apachefriends.org/download.html">XAMPP installiert</a> und verwendet. XAMPP ist eine Apache-Distribution, welche mit MariaDB, PHP und Perl kommt. XAMPP wird auch im weiteren Verlauf der Dokumentation verwendet.


## Was kommt als Nächstes?

Damit das Projekt benutzt werden kann, müssen folgende Schritte ausgeführt werden:

1. <a href="#herunterladen-des-repositories">Lade dieses Repository herunter</a>
2. <a href="#importieren-der-datenbank">Importiere die Datenbank</a>
3. <a href="#konfigurieren-der-datenbankverbindung">Konfiguriere die Datenbankverbindung</a>
4. <a href="#starten-des-servers-und-der-datenbank">Starte deinen Server und deine Datenbank</a>

---

#### Herunterladen des Repositories:

<a href="https://github.com/official-tsand/projekt-kafka">In diesem Repository</a> befindet sich rechts oben unter "Code" die Möglichkeit, das Repository herunterzuladen.

<img width="380" alt="bild1" src="https://user-images.githubusercontent.com/43005321/185433843-3f00ab88-b3ed-4975-971c-512286450762.png">

Das Projekt muss in den `htdocs` Ordner geladen werden, sonst funktioniert es nicht. Der Pfad lautet (ursprünglich): `C:\xampp\htdocs`.

Falls der Source-Code als ZIP-Datei heruntergeldaden wurde, dann bitte jenen Ordner vorab extrahieren. Der alte ZIP-Ordner kann/sollte gelöscht werden.
Im extrahierten Ordner gibt es einen weiteren Ordner mit dem gleichen Namen:

<img width="330" alt="bild5" src="https://user-images.githubusercontent.com/43005321/185433912-81a61c5c-cdd5-4424-86ad-140686e442d8.png">

Um diese (unnötige) Redundanz zu vermeiden, empfiehlt es sich **nur den inneren** Ordner in den `htdocs` Ordner zu verschieben.

Gratulation 🥳 Du hast nun alle notwendigen Dateien! Wir sind aber noch nicht ganz fertig...

---

#### Importieren der Datenbank:

Steige in _phpmyadmin_ ein. _phpmyadmin_ erreichst du über folgende URL: `localhost/phpmyadmin`. Sobald man in _phpmyadmin_ eingestiegen ist, befindet sich im linken Menü eine Schaltfläche mit dem Namen _Neu_. Klicke auf diese und erstelle eine Datenbank mit dem Namen `db_kafka`.

<img width="633" alt="image" src="https://user-images.githubusercontent.com/43005321/185392572-002ceb07-3902-4979-a0de-a5e5d0f10792.png">

<img width="686" alt="image" src="https://user-images.githubusercontent.com/43005321/185392745-2c1f3ec7-2b2d-4d17-9e3e-ae805f75a4ee.png">

Nachdem die Datenbank erstellt wurde, schaue zurück in den Projektordner. Dort gibt es einen Ordner `sql`: in dem befindet sich eine Datei namens `db.sql`. Klicke *unbedingt* im Menü auf die Schaltfläche mit dem Namen der neu-erstellten Datenbank. Ziehe erst dann die Datei `db.sql` irgendwo ins _phpmyadmin_ Interface hinein. Zum Schluss noch einmal die Seite aktualisieren.

Wenn alles passt, solltest du eine Datenbank mit dem Namen `db_kafka` und 4 Tabellen [users, quotes, comments and replies] haben. Gratulation 🥳!

---

#### Konfigurieren der Datenbankverbindung:

Wenn XAMPP frisch installiert wurde bzw. die Zugangsdaten nicht geändert wurden, dann kann der folgende Abschnitt ignoriert werden.

Doch falls die Zugangsdaten geändert wurden oder die Datenbank anders benannt wurde, dann muss sie neu konfiguriert werden...

Im Projektordner befindet sich unter `classes` die `dbconnection.cls.php` Datei. Öffne diese in deinem bevorzugten Text-Editor oder IDE.

Suche nach folgendem Abschnitt:

<img width="545" alt="image" src="https://user-images.githubusercontent.com/43005321/185395032-9e2a2b80-937e-4523-abac-28bf2697f654.png">

Ändere die Daten dementsprechend und speichere die Datei. Super 🥳

---

#### Starten des Servers und der Datenbank:

Öffne nun das XAMPP-Control-Panel (am besten als Administrator):

<img width="493" alt="bild6" src="https://user-images.githubusercontent.com/43005321/185432502-779d3b06-d9a6-4f28-bfd0-366c1d26661b.png">

Dort starte nun _Apache_ und _MySQL_:

<img width="493" alt="bild6" src="https://user-images.githubusercontent.com/43005321/185432552-43578e06-640e-41d4-a705-3315473eb4ae.png">

Insofern keine Fehler aufgetreten sind, bist du startklar 🥳


## Fertig!

Wenn du den oberen Anweisungen gut folgen konntest, dann bist du jetzt bereit **kafka** zu benutzen. Gib dafür in den Browser folgende URL ein: `localhost/projekt-kafka-main`.

Falls die Bedienung unklar sein sollte, dann schau in den nächsten <a href="#bedienung">Abschnitt</a> für mehr Informationen.

### Bedienung

Die Funktionsknöpfe im `header` besitzen folgende Funktion (v.l.n.r):
* _Persönliche Zitate_: schau dir *nur* deine selbst-erstellten Zitate an. Die anderen Funktionsknöpfe funktionieren weiterhin
* _Sortiere nach Datum (absteigend)_: die neuesten Zitate erscheinen zuerst
* _Sortiere nach Datum (aufsteigend)_: die ältesten Zitate erscheinen zuerst
* _Poste ein Zitat_: erstellt einen Zitat-eintrag (von **dir** erstellt) 

## Du, als Programmierer, darfst dich auf tolle neue Funktionen freuen!

**kafka** ist noch lange nicht fertig. Was heißt das konkret?

Das bedeutet:
* Sicherheitsupdates, die gegen PHP-Hijacking schützen!
* Funktionsupdates, die dir mehr Freiheit beim Anpassen deines Profils schenken!
* Layoutupdates, die die Verwendung von **kafka** noch einfacher machen!

---

Aber bis dahin, noch viel Spaß beim Verwenden 🧑‍💻
