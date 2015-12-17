# GSA Online Plus
Code repo for the GSA Online Plus Project.

## Set up
We use Vagrant for setting up a virtual machine in development. Clone this repo and run `vagrant up` to get a production-like environment with all dependencies installed.

In production, the bootstrap.sh-script may be used as a guideline for installing dependencies, but should not be run directly.

### Depenedencies
* Vagrant (https://www.vagrantup.com/)
* *Vagrant-Cachier* (optional, https://github.com/fgrehm/vagrant-cachier)

## Running
Use `vagrant ssh` to ssh into the VM. The project directory is symlinked into `/vagrant`; changes made on the host will also be made on the client and vice versa. Use `bin/rake` for running Rake tasks and `bin/rails` for running rails. The server can be startet with `bin/rails server -b 0.0.0.0` and will be available to the host on http://localhost:3000.

Please make sure to run everything but git inside the VM, to ensure compatibility. Even if you have rails installed on the host, running it's scaffolding-functions may be harmful if the host- and the target-version differ.

In production, there is currently no preferred way for daemonizing the server.

If you make changes to the Gem- or Bower-Dependencies, you'll have to run `bundle install` or `bower install` respectively.

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
* BSCW https://bscw.uni-koblenz.de/bscw/bscw.cgi/3262854