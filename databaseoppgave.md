# Databaseoppgave

## Oppgave 2

a. Velg alle jentene i hver av klassene:

```sql
SELECT 'Fornavn', 'Etternavn', FROM 'elev' WHERE 'Kjonn'="J" GROUP BY 'Klasse';
```

b. Velg alle guttene i klasse 2:

```sql
SELECT 'Fornavn', 'Etternavn' FROM 'elev' WHERE 'Klasse'="2" AND 'Kjonn'="G"
```

c. Tell antall jenter i klasse 2:

```sql
SELECT COUNT('Kjonn') FROM 'elev' WHERE 'Kjonn'="J" AND 'Klasse'="2"
```

d. Velg alle elever med Fornavn som starter på J:

```sql
SELECT 'Fornavn', 'Etternavn' FROM 'elev' WHERE 'Fornavn' LIKE "J%";
```

e. Velg alle elever med fornavn som starter på M og går i klasse 2:

```sql
SELECT 'Fornavn', 'Etternavn' FROM 'elev' WHERE 'Fornavn' LIKE "M%" AND 'Klasse'="2";
```

f. Velg alle elever med Fotball som hobby:

```sql
SELECT 'Fornavn', 'Etternavn', 'Hobby' FROM 'elev' WHERE 'Hobby'="Fotball";
```

## Oppgave 4

```sql
UPDATE 'elev' SET 'datamaskin'='1' WHERE 'ElevID'=3 OR 'ElevID'=6 OR 'ElevID'=9;
UPDATE 'elev' SET 'datamaskin'='2' WHERE 'ElevID'=2 OR 'ElevID'=5 OR 'ElevID'=8;
UPDATE 'elev' SET 'datamaskin'='3' WHERE 'ElevID'=1 OR 'ElevID'=4 OR 'ElevID'=7 OR 'ElevID'=10;
```

## Oppgave 5

```sql
ALTER TABLE 'elev' ADD FOREIGN KEY ('datamaskin') REFERENCES 'datamaskin'('DatamaskinID');
```

## Oppgave 6

```sql
SELECT * FROM 'elev' ORDER BY 'Fornavn' ASC;
```

## Oppgave 7 (Denne funker ikke, enda)

```sql
SELECT COUNT(*) AS 'Antall' FROM 'elev' WHERE 'Antall' => 2 GROUP BY 'Klasse'; 
```
