# synology-videostation-theporndb
Video information plugin for Synology Video Station based on [ThePornDB](https://metadataapi.net/)

## Usage
* Get [a personal API token for ThePornDB](https://metadataapi.net/user/api-tokens).
* Download the latest plugin from the releases.
* Synology made a special case for their own plugin to use a configurable API key, but as far as I can tell there is no way for another plugin to do it. So you have to modify the plugin yourself to get your own API token...
  * If you have an SSH access to your Synology server, you can modify the plugin in place after the upload (check out the `/volume1/@appdata/VideoStation/plugins/` directory).
  * If you don't, you can modify the archive before uploading the plugin to your Synology server:
    * Add the following line to the `loader.sh` file at the start of the script (after the _shebang_):
    ```
    METADATA_PLUGIN_APIKEY=<api_token>
    ```
* [Upload the plugin to your Synology server](https://kb.synology.com/en-us/DSM/help/VideoStation/metadata?version=7#b_7).

That's it. Few important notes:
* VideoStation expects information for directors and writers, which are not available on ThePornDB. So they will be set to _Unknown_.
* Websites and scenes are handled like TV Shows in VideoStation.
  * _TV Shows_ release dates will be wrong, as will episode and season numbers.
  * When searching for a scene, put the name of the website **and** the name of the scene in the search box.
