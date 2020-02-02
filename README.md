
Everyone has some passwords, keys, or codes that are important to give to others if we die or are incapacitated. They might range from server passwords to the key to feed our cats.

Often it is desireable to divide access so that some combination of trusted people can combine their parts of a code... perhaps a system where 3 of 5 people must agree to access the information.

Actually implementing this is a lot of work, however. It's easy to divide a password in a way that requires ALL people to agree - just give them each a few characters of the password. Implementing schemes where only SOME people need to agree is harder. Shamir's Secret Sharing is the oft-cited method; however, it is complicated for most people. 

To make it easier for my trusted people to gain access in an emergency, this script created nested encrypted zip files. Zip has existed for a long time and is generally available on every operating system.

## Configure
To start, edit it the `config.yaml` file to add your trusted people's names, set the number of passwords needed, etc. 

```
---
masterFilename: secret.txt
passwordLength: 24
requiredPasswords: 3

trustedIndividuals:
  - Kermit
  - Piggy
  - Ralph
  - Gonzo
  - Fozzy


```

Keep the number of trusted people low as the number of permutations grows quickly to be larger than your computer can likely handle. 

Set the number of passwords that are required. 

Set the name of your secret file that will ultimately be released when enough keys are combined. Again, keep this small because you will have lots of copies. You could have text, another zip file, video, etc.

The best `secrets.txt` file would be a small set of instructions to a final file... perhaps where to find the file and its password. When you need to update your secrets, just updated that file instead of redistributing to everyone. Also, you can physically protect that final file on a USB drive in a safe or what not. It's also probably a better idea than trusting your real secrets to some program like this that you downloaded.

## Running it.
`php build.php` should be all you need. As each nested file is created, it will give you a dot for progress. At the end of generation, it outputs a list of passwords that were used. You can safely distribute all of the zip files together, but only give each trusted person their password. 

```
............................................................
Your nested encryption files are in the output directory.

The passwords are:
Kermit: KusvUbUXAV9rNNmEQKthqdna
Piggy: DHuHjucDMyFKWgXXVVwRSRe3
Ralph: 5rnPJRL7V4da9qTdr1An9aeC
Gonzo: FWWCNKwHgv5fk6PwCjWdxsPd
Fozzy: E2CAHoeJLyqhCvpBR1X3EzPc

rabollig@lancelot:~/split_encryption$ cd output/
rabollig@lancelot:~/split_encryption/output$ ls
10-Piggy-Fozzy-Gonzo.zip  24-Piggy-Ralph-Gonzo.zip   38-Ralph-Gonzo-Kermit.zip  51-Gonzo-Kermit-Piggy.zip
11-Gonzo-Piggy-Fozzy.zip  25-Fozzy-Gonzo-Kermit.zip  39-Gonzo-Kermit-Ralph.zip  52-Kermit-Gonzo-Piggy.zip
12-Piggy-Gonzo-Fozzy.zip  26-Gonzo-Fozzy-Kermit.zip  3-Fozzy-Ralph-Gonzo.zip    53-Piggy-Kermit-Gonzo.zip
13-Fozzy-Ralph-Piggy.zip  27-Fozzy-Kermit-Gonzo.zip  40-Kermit-Gonzo-Ralph.zip  54-Kermit-Piggy-Gonzo.zip
14-Ralph-Fozzy-Piggy.zip  28-Kermit-Fozzy-Gonzo.zip  41-Ralph-Kermit-Gonzo.zip  55-Ralph-Piggy-Kermit.zip
15-Fozzy-Piggy-Ralph.zip  29-Gonzo-Kermit-Fozzy.zip  42-Kermit-Ralph-Gonzo.zip  56-Piggy-Ralph-Kermit.zip
16-Piggy-Fozzy-Ralph.zip  2-Gonzo-Fozzy-Ralph.zip    43-Fozzy-Piggy-Kermit.zip  57-Ralph-Kermit-Piggy.zip
17-Ralph-Piggy-Fozzy.zip  30-Kermit-Gonzo-Fozzy.zip  44-Piggy-Fozzy-Kermit.zip  58-Kermit-Ralph-Piggy.zip
18-Piggy-Ralph-Fozzy.zip  31-Fozzy-Ralph-Kermit.zip  45-Fozzy-Kermit-Piggy.zip  59-Piggy-Kermit-Ralph.zip
19-Gonzo-Ralph-Piggy.zip  32-Ralph-Fozzy-Kermit.zip  46-Kermit-Fozzy-Piggy.zip  5-Gonzo-Ralph-Fozzy.zip
1-Fozzy-Gonzo-Ralph.zip   33-Fozzy-Kermit-Ralph.zip  47-Piggy-Kermit-Fozzy.zip  60-Kermit-Piggy-Ralph.zip
20-Ralph-Gonzo-Piggy.zip  34-Kermit-Fozzy-Ralph.zip  48-Kermit-Piggy-Fozzy.zip  6-Ralph-Gonzo-Fozzy.zip
21-Gonzo-Piggy-Ralph.zip  35-Ralph-Kermit-Fozzy.zip  49-Gonzo-Piggy-Kermit.zip  7-Fozzy-Gonzo-Piggy.zip
22-Piggy-Gonzo-Ralph.zip  36-Kermit-Ralph-Fozzy.zip  4-Ralph-Fozzy-Gonzo.zip    8-Gonzo-Fozzy-Piggy.zip
23-Ralph-Piggy-Gonzo.zip  37-Gonzo-Ralph-Kermit.zip  50-Piggy-Gonzo-Kermit.zip  9-Fozzy-Piggy-Gonzo.zip
```

To gain access, a trusted person decides which chain of keys they have passwords for. The first person unzips the file with their password to find another zip file inside to the next person. This continues until the last person gets your masterFilename file.

```
rabollig@lancelot:~/$ cd output
rabollig@lancelot:~/output$ unzip 2-Gonzo-Fozzy-Ralph.zip 
Archive:  2-Gonzo-Fozzy-Ralph.zip
[2-Gonzo-Fozzy-Ralph.zip] Fozzy.zip password: 
 extracting: Fozzy.zip               
rabollig@lancelot:~/output$ unzip Fozzy.zip 
Archive:  Fozzy.zip
[Fozzy.zip] Ralph.zip password: 
 extracting: Ralph.zip               
rabollig@lancelot:~/output$ unzip Ralph.zip 
Archive:  Ralph.zip
[Ralph.zip] secret.txt password: 
 extracting: secret.txt             
```

# Notes
This script is packaged with all outside libraries that are needed, other than PHP, which is available on many platforms. 

Dependencies are included to make it easier to install and run, but experienced users might want to use `composer` to make sure they are up-to-date.

# Warnings
Test unzipping some files - some systems don't actually encrypt!

Not all zip programs use the latest AES encryption, and it is difficult to tell which encryption is used on a file. You should make sure your system is up to date and consider using other security means as a second factor (such as physical controls).


