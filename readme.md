# GSA Online Plus
Code repo for the GSA Online Plus Project.

## Set up
We use Vagrant for setting up a virtual machine in development. Clone this repo and run `vagrant up` to get a production-like environment with all dependencies installed. Our base box is [Scotch Box](https://box.scotch.io/). The project directory is symlinked into `/var/www`; changes made on the host will also be made on the client and vice versa. `/var/www/public` is the root of the webserver.

It has yet to be decided if we'll replicate the box environment on the production server or if we'll run the Vagrant VM there too.

SSH into the VM (`vagrant ssh` or use vagrant@192.168.33.10 with password *vagrant* with your own client). Execute the following commands sequentially for the first-time setup. After that, run individual commands when needed (eg. if dependencies change).

``` bash
cd /var/www # Change to the project dir inside the VM
composer install # Fetch php-dependencies
cp .env.example .env # Create your own .env-configuration from a template
php artisan key:generate # Generate a new app-key
php artisan migrate # Migrate the database
npm install # Fetch frontend-dependencies
gulp # Compile/copy frontend-dependencies into public
```

We use the provided Scotch Box MySQL Databse, the application is configured accordingly (in `config/database.php`).

### Common Errors
* *Permission denied*, *File does not exist* and other File-Errors: Sometimes the gitignore excludes directories that need to exist for the application. This is a configuration error and should be reported to <mbrack@uni-koblenz.de>.
* *You don't have permission to access / on this server.* The VM is not running and you are once again pestering some random server on the internet. Use `vagrant up` in the project dir to start the VM. On some occasions you'll have do reboot the host machine prior to that (not sure why, but it seems to help).

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
