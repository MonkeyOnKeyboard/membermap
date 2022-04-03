# membermap
Ilch 2.x Modul zur Anzeige und Benutzung einer Membermap

#Membermap Optionen
Das Modul beinhaltet 3 verschiedene Mapservice Optionen.
1. MapQuest
2. GoogleMaps
3. Open Street Maps

Zu 1. MapQuest API bietet nach der Registrierung und Einrichtung des API-Schlüssels 15000 Zugriffe im Monat frei an. Die Besonderheit, hier wird die Adresse beim Zugriff
      auf die Map automatisch umgewandelt in Längen und Breitengrad Koordinaten. Was natürlich Zugriffe bedeutet.

Zu 2. Ähnlich zu MapQuest bietet GoogleMaps API ein KostenPlan mit Zugriffen, die im Monat kostenlos sind. Hierzu bitte sich Informieren.

Zu 3. Open Street Maps läuft hingegen Autonom ohne API Schlüssel und in der Regel komplett kostenlos

# Installation

alle Dateien, in ihrer Ordnerstrucktur hochladen (*Ilch2Root*/application/modules/membermap/)

Nach Uploaden aller Datein muss das Modul im Backend bei der Module Übersicht unter Nicht installierte Module installiert werden.

#### Usage
Nach der Installation musst du dir zunächst im Admincenter unter `Module > Membermap > Einstellungen` den Mapservice auswählen und dann entsprechend den Api-Schlüssel des Service eintragen. 

Anschließend muss das Modul entsprechend im Menü verlinkt werden.

# Haftungsausschluss
Ich übernehme keine Haftung für Schäden, welche durch dieses Modul entstehen. 
