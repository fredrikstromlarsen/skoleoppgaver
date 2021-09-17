# Stavespill

## Behov

### Hva skal spillet være?

Et stavespill som kan hjelpe brukeren å stave topp 100/200 ord. Brukeren vil kunne lære å stave ord ved skriver et ord de hører. Det kan også være lurt å ha en enkel definisjon av ordet. En serie med oppgaver er 5 ord, hvert femte ord vil brukeren se et bilde av sin favorittgreie (f.eks. Dennis Vareide).

### Liste over nødvendige funksjoner

-   En loginside. Slik som kahoot; 6-talls kode for å bli med i en session, deretter skriv eget navn.
-   En side for å se og høre et ord. Vi kan kalle denne siden "Se og Hør".
-   En side for å høre ordet og manuelt stave det. Denne kalles "Hør og Stav".
-   Hver serie skal gi en sum poeng til brukeren avhengig av hvor mange de klarer.
-   En Leaderboard som viser alle brukerenes rangering sammenlignet med resten av brukerene.
-   Topp 100/200 mest brukte ord.
-   En hjelpende hånd, på en eller annen måte, hint til hvilken bokstav som er neste.

## Applikasjon



## Fremgangsmåte

### Login

-   DB table for `bruker` trenger:
    -   Fornavn
    -   Etternavn (Vises som initialer)
    -   6-tegn-id med tall og bokstaver
    -   Score 
    -   Favorittbilde (Som premie når brukeren klarer en serie med oppgaver)
-   Så enkel som mulig, sterke farger.
-   Score skal nullstilles daglig.
  
### Ord

-   Databasetabell for `ord` trenger:
    -   100/200 mest brukte ord.

### Oppgaveside

-   Staveinput bør vise en rute for hver bokstav, det skal være like mange ruter som bokstaver i ordet.
-   En stor knapp med volumikon kan trykkes for å høre ordet. Kanskje annenhver sakte og vanlig stemme.
-   Definisjon på ordet skal stå under ordet/volumknappen.
-   Exit-knapp.
-   "Jeg vet ikke"-knapp.
-   Hver oppgave som fullføres skal gi brukeren noen poeng. Ved 2 eller flere forsøk gir det 50% poeng.
-   Når en serie med oppgaver er fullført skal man kunne se et premiebilde (f.eks. bilde av Dennis Vareide).
-   Man vil også kunne se et live leaderboard høyre side.

### Leaderboard

-   Backend må være Javascript hvis ting skal oppdatere seg live.