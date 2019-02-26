# Invox

## Description 
**Invox** è lo strumento *in cloud* indispensabile per una corretta contabilità aziendale.
Facile ed intuitivo, è un software gestionale particolarmente adatto alle società di servizi e ai liberi professionisti che possono gestire e condividere via web i dati, con più persone e su diversi dispositivi.

**Invox** è una piattaforma *open source* ed è la chiave per avere sempre una chiara rappresentazione della situazione economico-finanziaria della propria azienda.

## Local deployment with Vagrant

### Requisites
Invox può essere installato in locale utilizzando la stessa configurazione usata lato server.

In caso si voglia installare su macchina virtuale, nel progetto è presente una configurazione di [Vagrant](https://www.vagrantup.com/). 
La configurazione è stata creata tramite [PuPHPet](https://puphpet.com/) e si trova nella cartella *puphpet*.
Per usare Vagrant è richiesta l'installazione di [VirtualBox](https://www.virtualbox.org) o [VMware](http://www.vmware.com/).

Consigliamo di utilizzare Vagrant 1.8.5 e VirtualBox 5.1.6. Eventuali versioni più o meno recenti non sono testate da noi e pertanto non ne garantiamo il funzionamento.

### Primo avvio
Una volta installato tutto il software necessario, per creare la macchina virtuale basta lanciare il comando `vagrant up` all'interno della cartella principale del progetto.
Vagrant provvederà a scaricare e configurare la macchina virtuale. 
Per entrare tramite SSH nella macchina lanciare il comando `vagrant ssh`.
La directory principale del progetto sarà montata tramite NFS nelle directory `/var/www/vhosts` e `/vagrant`.

### Spegnimento
Per spegnere la macchina virtuale lanciare il comando il comando `vagrant halt` nella directory del progetto.

### Aggiornamento ambiente
Il file `{project-dir}/puphpet/config.yaml` contiene la configurazione della macchina, tra cui specifiche hardware, sistema operativo, pacchetti installati e configurazione di rete.
Una volta modificato il file, per applicare le modifiche alla macchina, lanciare il comando `vagrant provision` in caso la macchina sia accesa. 

> In alternativa lanciare il comando `vagrant up --provision` che provvederà a lanciare la macchina e applicare le modifiche.

### Configurazione hosts
L'indirizzo IP della macchina virtuale è *100.0.0.112*, mentre il nome host è *invox.local*. Entrambe le configurazioni si trovano nel file `{project-dir}/puphpet/config.yaml`.

Modificando il file hosts della propria macchina locale è possibile cambiare l'indirizzo di accesso alla macchina.
Nei sistemi operativi OSX e Linux il file è `/etc/hosts`, mentre su Windows è `c:\Windows\System32\Drivers\etc\hosts`.
La nuova riga riguardante Invox deve essere simile a questa: `100.0.0.112 invox.local`.

### Credenziali MySql
Il server MySql presente nella macchina contiente l'utente *root* avente password *root*. 

## Preparazione server
Invox utilizza [Composer](https://getcomposer.org/) per gestire le sue dipendenze.

### Installazione Composer
Seguite le istruzioni presenti nella [documentazione](https://getcomposer.org/doc/00-intro.md) per installare *Composer* nella vostra macchina.

> Se state utilizzando *Vagrant* potete saltare questo step, *Composer* é pre-installato nella macchina virtuale.

### Installazione dipendenze
Nella directory del progetto eseguite il comando `composer install`. 
Tutte le dipendenze del progetto saranno scaricate e installate nella cartella `{project-dir}/vendor`.
Alla fine del processo sarà richiesta la compilazione dei parametri del progetto, come nome e credenziali del db, credenziali del mailer etc..
I parametri del progetto Symfony sono contenuti nel file `{project-dir}/app/config/parameters.yml` e possono essere modificati in qualsiasi momento.

### Creazione database
Lanciando il comando 

    php {project-dir}/bin/console doctrine:database:create

verrà creato il database.

Successivamente, eseguendo

    php {project-dir}/bin/console doctrine:schema:create

saranno create le tabelle al suo interno. 

> Di default Symfony creerà il database per l'ambiente di sviluppo (dev). Per cambiare ambiente aggiungete ai comandi il parametro -e {environment-name} (es. -e prod).
> Gli ambienti disponibili su Invox sono dev, test, stage e prod. Per ulteriori informazioni sugli ambienti in Symfony consultare la [documentazione](https://Symfony.com/doc/current/configuration/environments.html).

> Se si sta utilizzando una macchina Vagrant i database di dev, test e prod sono già presenti, pertanto è necessario lanciare solo il comando per la creazione delle tabelle.

> Per ulteriori informazioni sui comandi della console di Symfony consultare la [documentazione](http://Symfony.com/doc/current/components/console.html)

### (Optional) Loading fixture 
Invox mette a disposizione alcune fixture di esempio utili per lo sviluppo. 
Per caricarle basta lanciare il comando `php {project-dir}/bin/console hautelook:fixtures:load`.

### Executing tests

To run unit tests, execute

    vendor/bin/simple-phpunit
    
To run behavior tests, run PhantomJS in a terminal shell

    phantomjs --web-driver=4444 --disk-cache=false
    
then run the tests

    vendor/bin/behat 
