![Image](img/banner.png?raw=true)

<img src="https://img.shields.io/badge/License-Partially%20Open%20Source-blue"> <img src="https://img.shields.io/badge/Build-Community-brightgreen"> <img src="https://img.shields.io/badge/Device-Raspberry%20Pi%203B%2B%20%2F%204B-red"> <img src="https://img.shields.io/badge/Made%20In-Hong%20Kong-blueviolet">

[中文說明](README-zh-HK.md)

**Updates: As PHP (the language itself) is deprecating, we are translating our system to Go (Or golang) in the coming months. Data migration guidelines will be provided later. If you have any questions, feel free to create a new issue.**

# ArOZ Online System / aCloud
Personal Cloud Platform with Web Desktop Environment for Raspberry Pi 3B+ or 4B. A place to start cloud music and video streaming, data storage, light-weight office work / text processing, 3D printing file previews, Cloud programming IDE and more!

**WARNING! THIS SYSTEM IS STILL IN BETA EDITION.**

## Getting Started
These instructions will guide you through the deployment of a copy of ArOZ Online System on your own Raspberry Pi or other low power, Linux running SBCs for community review and testing purposes. Read more on the [Full System Documentation](https://tobychui.github.io/ArOZ-Online-System/).

### Docker
Follow the instructions from this repo: https://github.com/Saren-Arterius/aroz-dockerize

### Prerequisites
The following packages are required for the system to run on your Linux system.
- apache2
- libapache2-mod-xsendfile
- php libapache2-mod-php php-cli php-common php-mbstring php-gd php xml php-zip
- php-mysql (Optional)
- libav-tools / ffmpeg (Optional)
- samba (Optional)

To install the package above, copy and paste the following lines into your ssh terminal line by line.
```bash
# Add the following line if you are using a fresh install of Debian Buster
sudo apt-get install unzip net-tools ntfs-3g dosfstools -y
sudo apt-get update
sudo apt-get install -y apache2
sudo apt-get install -y php libapache2-mod-php php-cli php-common php-mbstring php-gd php-xml php-zip 
sudo apt-get install libapache2-mod-xsendfile
# The lines below are optional. But it is recommended to install them for future uses
sudo apt-get install php-mysql
# Use libav-tools instead of ffmpeg if you are still using Debian Jessie
sudo apt-get install ffmpeg
sudo apt-get install samba
```
### Prebuilt Image File
To install ArOZ Online System to your Raspberry Pi, you could use the prebuilt image file for Raspberry Pi 4B / 3B+. The image is in the link below:

> WORK IN PROGRESS

### Manual Installation

#### Overview

ArOZ Online System was on Debian Jessie and Debian Buster trials only. Before installing the ArOZ Online System, you need to install the necessary software packages as shown in the [Prerequisites](#Prerequisites) section.

#### Configuring PHP

ArOZ is not compatiable with the default PHP settings.

Edit `php.ini` to increase the max file upload size setting. On Debian systems, the `php.ini` file can usually be found in `/etc/php/{php-version}/apache2/php.ini`. Change these two lines below as follows:
```
upload_max_filesize = 2048M
post_max_size = 2048M
```

#### Configuring Permissions

Certain features of ArOZ Online System requires explicit authorization through configuration files.

If you are not familiar with `sudoer` configuration syntax, open `/etc/sudoers` with `nano` and add the following to the end of file.

```
www-data ALL=NOPASSWD: /usr/bin/mount, /sbin/mount.ntfs-3g,  /usr/bin/ntfs-3g, /usr/bin/umount, /sbin/halt, /sbin/reboot, /sbin/poweroff, /sbin/ifconfig, /sbin/ip, /sbin/mkfs.ntfs, /sbin/mkfs.vfat
```
**(Use the above settings only if you are hosting the system in your local area network. Please adjust this to suit your needs if you are allowing the access of the system from the Internet.)**

If you are familiar of the system security and want to have better control over it, add the lines that fits your need according to the selections below.

- Allow the system to mount and unmount your USB drives

      www-data ALL=NOPASSWD: /usr/bin/mount, /sbin/mount.ntfs-3g, /usr/bin/umount
  
- Allow system to be powered off via the Web UI

  ```
  www-data ALL=NOPASSWD: /sbin/halt, /sbin/reboot, /sbin/poweroff
  ```

- Allow system to access local area network IP address and WiFi network settings
    ```
    www-data ALL=NOPASSWD: /sbin/ifconfig, /sbin/ip
    ```

- Allow system to format and erase partitions
    ```
    www-data ALL=NOPASSWD: /sbin/mkfs.ntfs, /sbin/mkfs.vfat
    ```

> TO BE ADDED

#### Apache Settings

Edit `/etc/apache2/apache2.conf`, add the following two lines to the end of file

  ```
XSendFile on
XSendFilePath /media
  ```

#### Creating directories

Create directory at /media/storage1 and /media/storage2

  ```bash
sudo mkdir /media/storage1 /media/storage2
  ```
#### Installing ArOZ Online to webroot directory

You will need to download and install the ArOZ Online System to your webroot (`/var/www/html/`).
Navigate to the web root using the following command

  ```bash
cd /var/www/html/
  ```

And then download the repo as zip, unzip the `src` folder into `/var/www/html` and rename `src` to `AOB`.
This could be done using WinSCP if you are using Windows. Otherwise, use `git clone` command similar to the example below. Make sure the installation of `git` is  being completed by using ```sudo apt-get install git``` before running the `git` command.

  ```bash
git clone https://github.com/tobychui/ArOZ-Online-System/
sudo mv ArOZ-Online-System/src ./AOB
sudo rm -rf ./ArOZ-Online-System
  
sudo mkdir -p "/etc/AOB"
sudo chmod 777 -R "/etc/AOB"
sudo chmod 777 -R ./AOB
sudo chown -R www-data ./
  ```

#### Testing

Open your default browser and visit the http://{Raspberry_Pi_IP_Address}/AOB/ and follow the on-screen guide for setting up a new user.

## Preview / Screenshots
![Image](img/screenshots/audio.png?raw=true)
![Image](img/screenshots/photo.png?raw=true)
![Image](img/screenshots/video.png?raw=true)
![Image](img/screenshots/listmenu.png?raw=true)
![Image](img/screenshots/fileexp.png?raw=true)
![Image](img/screenshots/async-fileopr.png?raw=true)
![Image](img/screenshots/diskman.png?raw=true)
![Image](img/screenshots/settings.png?raw=true)

Click <a href="https://github.com/tobychui/ArOZ-Online-System/tree/master/img/screenshots">here</a> for more preview screenshots

## Versioning
Different major change in versioning will lead to an upgrade to the codename. Here are a list of versions ArOZ Online System provides at the moment. 

| Version Number | Code Name | Major Change | Type (Barebone / Pre-release / Full* ) |
|----------------|-----------|--------------|---------------------------------------------|
| Before Beta 1.1.4     | Aloplex                  | N/A          | Full                         |
| Before Beta 1.2.8     | Sempervivum Tectorum     | N/A          | Full                         |
| Beta 12-06-2019       | aCloud                   | Init Release | Pre-release                  |

*Full versions are only available for internal developers or testers for testing purposes only.

## Author
### Developers
(Blame them if you encounter any bugs within the system)
* tobychui - Project initiator / System Developer / Core Modules designer and programmer
* <a href="https://github.com/yeungalan">yeungalan</a> - Module maintainer / Network Setting Module developer

### Beta Testers
(Find them if you want to know how to use a specific function in the system)
* <a href="https://github.com/aceisace">aceisace</a>
* <a href="https://github.com/RubMing">RubMing</a>

## License
Build-in Multimedia Modules (Audio / Photo / Video) - MIT License

Desktop Module (Desktop) and its utilities - *All Right Reserved*, MIT License ONLY on Raspberry Pi and other ARMv6, v7 or ARM64 based SBC for non-commercial purposes.

All core scripts and binary files under root (./) and System Script Folder (SystemAOB/*) - tobychui feat. ArOZ Online Project, All Right Reserved.

All other files or modules that is not covered by the license above - See the module's license for more information.

THIS SOFTWARE IS ONLY FOR PERSONAL AND NON COMMERCIAL USE ONLY. RE-SELL ,DISTRIBUTE OR CLAIM THIS AS YOUR OWN WORK IS PROHIBITED

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*Please seek for author approval if you want to deploy this system for purposes other than personal (e.g. home NAS, private media server, automation control in your room etc) and educational (e.g. school projects, course demos etc)*

## Acknowledgments
Special thanks for the following projects which bring insights to this project.

TocasUI by Yami Odymel - https://tocas-ui.com/ 

Threejs by Mrdoob - https://threejs.org/

## Q&A
TO BE ADDED

## Buy me a coffee
Actually I don't drink coffee.
Send me something that would make me feel interested if you really want to :)
