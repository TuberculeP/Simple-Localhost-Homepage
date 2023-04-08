# Simple-Localhost-Homepage

A simple onepage file to replace the default Apache/Nginx folder view for localhost.

Optimized for Windows Laragon : https://laragon.org/

## Features

- File indexing with dynamic linking : detected websites will be linked to \*.test, folders to ./\*
- Folder ignore system with sqlite storage

## Setup

Just download the php & db file and put it at the root folder of Laragon/Valet/Any you want

Be sure to enable `fileinfo` & `sqlite` extension in your `php.ini`
