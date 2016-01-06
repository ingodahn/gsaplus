# GSA Online Plus
Code repo for the GSA Online Plus Project.

## Set up
We use Vagrant for setting up a virtual machine in development. Clone this repo and run `vagrant up` to get a production-like environment with all dependencies installed. Our base box is [Scotch Box](https://box.scotch.io/).

It has yet to be decided if we'll replicate the box environment on the production server or if we'll run the Vagrant VM there too.

Use `vagrant ssh` to ssh into the VM. The project directory is symlinked into `/var/www`; changes made on the host will also be made on the client and vice versa. `/var/www/public` is the root of the webserver. You will have to `composer install` to fetch php-dependencies and `php artisan migrate` (yes, you really want to) to migrate the database at least once before the server is fully operational.

We use the provided Scotch Box MySQL Databse, the application is configured accordingly (in `config/database.php`).

### Depenedencies
* Virtual Box (https://www.virtualbox.org/)
* Vagrant (https://www.vagrantup.com/)

## Running
The application is automatically served by an Apache Server. It should be available to the host at <http://192.168.33.10/>. The Scotch Box testpage (containing some very useful information about the VM's configuration) is available at <http://192.168.33.10/scotchbox.php>.

Please make sure to run everything but editing and git inside the VM, to ensure compatibility. Even if you have laravel etc. installed on the host, running it's scaffolding-functions may be harmful if the host- and the target-version differ.

## Glossary
* **User** Abstract term for a person using the system
* **Patient** Primary users of the system
* **Therapist** Supervise patients. There will be very few in the system.
* **Administrator** Administer the system
* **Assignment** Weekly writing-tasks for patients
* **Assignment-Template** Contain the task description for an assignment and may be reused
* **Code** Registration-codes, provide patients with a convenient way for registration
* **Response** Response of a therapist to a completed assignment

## People and Communications
* **Marco Brack** <mbrack@uni-koblenz.de> Developer
* **Sascha Zimmermann** <zimsa@uni-koblenz.de> Developer

* **Peter Ferdinand** <ferdinand@uni-koblenz.de> Head of IWM
* **Sergei Pachtchenko** <gektor@uni-koblenz.de>
* **Ingo Dahn** <dahn@uni-koblenz.de>
* **Astrid Wirth** <astrid.wirth@unimedizin-mainz.de>
* **Katje Böhme** <katja.boehme@unimedizin-mainz.de>
* **Rüdiger Zwerenz** <ruediger.zwerenz@unimedizin-mainz.de>

Please use our [Slack](https://iwm-unimedmainz.slack.com/messages/general/) or email for communication and [Trello](https://trello.com/b/NhCAw37H/gsa-softwareentwicklung) for Development-related Task-Tracking. There is also a [Trello Board](https://trello.com/b/GNS8jOrk/gsa-allgemein) for more general purposes.

## Links
* GitLab https://gitlab.uni-koblenz.de/iwm/gsa-online-plus
* Project-Slack https://iwm-unimedmainz.slack.com/messages/general/
* Software-Development Trello-Board https://trello.com/b/NhCAw37H/gsa-softwareentwicklung
* General Trello-Board https://trello.com/b/GNS8jOrk/gsa-allgemein
* BSCW https://bscw.uni-koblenz.de/bscw/bscw.cgi/3262854?client_size=1855x971
