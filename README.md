# COSC349Assignment1

This project uses 3 vagrant virtual machines to run an online tic tac toe application. Users can make accounts, log in, and play against other users, slowly building up statistics which are displayed. Score is also tracked which is evaluated on the leaderboard. It is a turn based sort of gameplay. You make one turn and then send it to you're opponent and wait till they send you a move back. There is also a database reporting feature through the terminal. There are a few preset accounts in the system for example so you don't have to set too much up but to see how games work you will have to make them yourself.

## Interactions
The web server runs the application on 127.0.0.1 that you can access through any web browser. It connects up to the database server (dbserver) in order to read data from and edit data in the MySQL database. The reporting server then also connects to the database server and creates a report from its data (NOT CURRENTLY WORKING).

## Installation
To activate create the virtual machines you will need to have both Vagrant and VirtualBox installed on your computer.

Vagrant: https://www.vagrantup.com/docs/installation
VirtualBox: https://www.virtualbox.org/wiki/Downloads

## Booting

Once installed, open the terminal and find your way to inside the COSC349Assignment1 directory.

```bash
cd <INSERT PATH HERE>/COSC349Assignment1
```

Then create the virtual machines

```bash
vagrant up --provider virtualbox
```

If the webserver does not boot quickly enough or if it times out, destroy with the destroy instructions below. Then open VirtualBox and try the command above again, this time right click on the virtual machine once it shows it shows up in VirtualBox and select "show".

## Usage

To use the web app, insert 127.0.0.1 into a web browser and you will be placed on the login / register page. Interact with it like any website

To use the database reporting, while in the project directory where the application was booted, transfer into the reporting virtual machine.

```bash
vagrant ssh reportserver
```

Then run the reporting script and follow its prompts

```bash
python /vagrant/reporting.py
```

To leave this virtual machine simply type logout

```bash
logout
```

## Halt or Destroy

Halting turns the virtual machines off, allowing them to be booted up again at a later date

```bash
vagrant halt
```

Destroying them deletes them and their contents. Follow the prompts as they pop up.

```bash
vagrant destroy
```
