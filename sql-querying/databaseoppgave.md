# Databaseoppgave

## Oppgave 2

a. Velg alle jentene i hver av klassene:

```sql
SELECT `Fornavn`, `Etternavn`, FROM `elev` WHERE `Kjonn`='J' GROUP BY `Klasse`;
```

b. Velg alle guttene i klasse 2:

```sql
SELECT `Fornavn`, `Etternavn` FROM `elev` WHERE `Klasse`=2 AND `Kjonn`='G';
```

c. Tell antall jenter i klasse 2:

```sql
SELECT COUNT(`Kjonn`) FROM `elev` WHERE `Kjonn`='J' AND `Klasse`=2;
```

d. Velg alle elever med Fornavn som starter på J:

```sql
SELECT `Fornavn`, `Etternavn` FROM `elev` WHERE `Fornavn` LIKE "J%";
```

e. Velg alle elever med fornavn som starter på M og går i klasse 2:

```sql
SELECT `Fornavn`, `Etternavn` FROM `elev` WHERE `Fornavn` LIKE "M%" AND `Klasse`=2;
```

f. Velg alle elever med Fotball som hobby:

```sql
SELECT `Fornavn`, `Etternavn`, `Hobby` FROM `elev` WHERE `Hobby`='Fotball';
```

## Oppgave 3

Lag en ny tabell som heter som “Datamaskin”, som viser 3 ulike datamaskin-modeller som tilhører elevene. Den skal minst 3 kolonner og minst 3 rader.

|DatamaskinID|Merke|OS|
|-|-|-|
|1|Lenovo|Windows 10|
|2|Acer|Windows 10|
|3|Apple|MacOS|

## Oppgave 4

Gi hver elev en datamaskin i “Elev”-tabellen i en ny kolonne. Kall kolonnen “datamaskin”. Den skal ha samme verdier som primærnøkkelen i  “datamaskin”-tabellen. 

```sql
UPDATE `elev` SET `datamaskin`=1 WHERE `ElevID`=3 OR `ElevID`=6 OR `ElevID`=9;
UPDATE `elev` SET `datamaskin`=2 WHERE `ElevID`=2 OR `ElevID`=5 OR `ElevID`=8;
UPDATE `elev` SET `datamaskin`=3 WHERE `ElevID`=1 OR `ElevID`=4 OR `ElevID`=7 OR `ElevID`=10;
```

## Oppgave 5

Lag en fremmednøkkel i tabellen “Elev” som linker mot “Datamaskin”-tabellen. Søk på nettet om hvordan man gjør det i PHPMyadmin eller som SQL-spørring. Gjør deretter det samme med tabellen klasse.

```sql
ALTER TABLE `elev` ADD FOREIGN KEY (`datamaskin`) REFERENCES `datamaskin`(`DatamaskinID`);
```

## Oppgave 6

Sorter elevene etter forbokstav I elev-tabellen. I kronologisk rekkefølge.

```sql
SELECT * FROM `elev` ORDER BY `Fornavn` ASC;
```

## Oppgave 7

Lag en query der dubare lister opp klasser med minst to elever. Sorter etter kolonnen “klasse” I kronologisk rekkefølge.

```sql
SELECT `Klasse`, COUNT(*) FROM `elev` GROUP BY `Klasse` HAVING COUNT(*) >= 2 ORDER BY `Klasse` ASC;
```

## Oppgave 8

Oppdater tabellen med en UPDATE, der du forandrer hobbyene til en av elevene.

```sql
UPDATE `elev` SET `Hobby`=`Prorgamering` WHERE `ElevID`=3;
```

## Oppgave 9

Legg til en ny elev med INSERT. 

```sql
INSERT INTO `elev`(`Fornavn`, `Etternavn`, `Klasse`, `Hobby`, `Kjonn`, `datamaskin`) VALUES ('Hans' ,'Larsen' ,3, 'Basketball','G',2);
```

## Oppgave 10

Slett en elev fra klassen (Elev-tabellen) med SQL.

```sql
DELETE FROM `elev` WHERE `ElevID`=10
```
