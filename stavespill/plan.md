# Stavespill

## Behov

### Hva skal spillet være?

Et stavespill som kan hjelpe brukeren å stave topp 100/200 ord. Brukeren vil kunne lære å stave ord ved skriver et ord de hører. Det kan også være lurt å ha en enkel definisjon av ordet. En serie med oppgaver er 5 ord, hvert femte ord vil brukeren se et bilde av sin favorittgreie (f.eks. Dennis Vareide).

### Liste over nødvendige funksjoner

-   En loginside. Slik som kahoot; 6-talls kode for å bli med i en session/gruppe, deretter skriver brukeren eget navn slik at jeg ikke trenger å ha en navnstandard for passord og brukernavn. Blir nesten som Slack.
-   En side for å se og høre et ord. Vi kan kalle denne siden "Se og Hør".
-   En side for å høre ordet og manuelt stave det. Denne kalles "Hør og Stav".
-   Hver serie skal gi en sum poeng til brukeren avhengig av hvor mange de klarer.
-   En Leaderboard som viser alle brukerenes totale score.
-   Topp 100/200 mest brukte ord.

## Applikasjon



## Fremgangsmåte

### Login
-   DB tabell for `session` trenger:
    -   ID
    -   Liste over brukere
    -   Brukerscore

-   DB tabell for `user` trenger:
    -   ID
    -   Score 
    -   Favorittbilde (Som premie når brukeren klarer en serie med oppgaver)

### Ord

-   Databasetabell for `words` trenger:
    -   ID
    -   100/200 mest brukte ord.
    -   Definisjon for hvert ord

Lyd for hvordan et ord sies blir lagret i en mappe: public/ord/{ord}.mp3, senere.


### Oppgaveside

-   Staveinput bør vise en rute for hver bokstav, det skal være like mange ruter som bokstaver i ordet.
-   En stor knapp med volumikon kan trykkes for å høre ordet. Kanskje annenhver sakte og vanlig stemme.
-   Definisjon på ordet skal stå under ordet/volumknappen.
-   Exit-knapp > Sender brukeren til forsiden.
-   "Jeg vet ikke"-knapp?
-   Hver oppgave som fullføres skal gi brukeren noen poeng. Ved 2 eller flere forsøk gir det 50% poeng.
-   Når en serie med oppgaver er fullført skal man kunne se et premiebilde (f.eks. bilde av Dennis Vareide).
-   Man vil også kunne se et live leaderboard høyre side.

### Leaderboard

-   Backend må være Javascript hvis ting skal oppdatere seg live.