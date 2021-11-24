# csv_phone_numbers_parser
Parser di file csv con elenchi di numeri telefonici, repository creata per scopi lavorativi

# FUNZIONAMENTO
Il funzionamento del parser è stato appositamente pensato per essere il più possibile user-friendly, è quindi possibile interagire attraverso i 2 semplici form messi a disposizione dell'utente: uno per l'inserimento di un numero telefonico da parsare e inserire in DB e un'altro per scegliere un file csv da usare per il parsing di più numeri telefonici.

Nel file "global.php" vengono definite le variabili globali "DB_HOST", "DB_USER" ecc.
Sono attualmente impostate per lavorare in localhost, se caricherete il file "african_numbers.sql" su un server non in localhost, bisogna modificare tali variabili in modo tale da puntare al DB corretto

I numeri di telefono inseriti manualmente verranno controllati e aggiunti al DB.

NB: Non trovo motivo di far visualizzare all'utente l'eventuale correzione eseguita su un numero, in quanto l'unica correzione possibile è l'aggiunta di "27" ad un numero di 9 cifre.
