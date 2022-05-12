# Stein Saks Papir - Socket.io

## Oppsett

1. Initialisering
   1.1 Søk etter lobby.
   1.2 Hopp til steg 2 om ledig lobby finnes.
   1.3.1 Opprett lobby.
   1.3.2 Vent på andre spillere.
   1.3.3 Hvis alle spillere er innmeldt, hopp til steg 2.

2. Spill
   2.1.1 Vent til begge spillere er klare.
   2.1.2 Broadcast spillstart.
   2.2 Vent på begge spillerhandlinger.

3. Resultat
   3.1 Broadcast resultat.
   3.2 Søk etter ny lobby (steg 1) eller spill igjen (steg 2).

## Variabler

```typescript
// Server:
const io = require('socket.io')(server);
let games: Array<object> = [
   ["socket-id", "socket-id"]
]
];
let actions: object = {
   "userid": "action",
   "userid": "action"
};
let gameHistory: Array<Object> = {
   0: [
      {
         "users": [163249342, 165064349],
         "actions": {
            163249342: 0,
            165064349: 2
         },
         "result": 1
      }
   ]
};


// Klient:
export const gameid;
let username: string = "user16234298";
let action: number = 0;
```