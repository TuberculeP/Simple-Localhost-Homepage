# Simple-Localhost-Homepage

A simple onepage file to replace the default Apache/Nginx folder view for localhost.

Optimized for Windows Laragon : https://laragon.org/

## Features

- File indexing with dynamic linking : detected websites will be linked to \*.test, folders to ./\*
- Folder ignore system with sqlite storage

![image](https://user-images.githubusercontent.com/91781579/230734192-2248f2bb-0768-4428-bf4a-c8df8e9c4ea2.png)


## Setup

Just download the php file and put it at the root folder of Laragon/Valet/Any you want

Be sure to enable `fileinfo` & `sqlite` extension in your `php.ini`
