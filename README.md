# WP Recent GitHub Repos
**Tested on**: WordPress 5.2.2
**Tags**: WordPress, GitHub, PHP, Repos

#Description


 WordPress plugin to show the recent GitHub repos In the sidebar.
The Plugin is very simple it read the GitHub api as json then it decode the json .

#Installation

Go to plugins -> Add New , then select the plugin as zip compressed file or you can upload it to the WordPress plugins folder (wp-content/plugins/) via FTP make sure to upload the plugin inside it's own folder so the full path will be :  (\wp-content\plugins\wp-github-repos\).

#Caching

The plugin save the retrieved data from GitHub for 5 hours so if you wanna delete the cache to do some testing you can uncomment these lines inside the plugin source:

			delete_transient($key);
			delete_transient($data);
			delete_transient($latest); 

#ScreenShots
[![](https://raw.githubusercontent.com/Alaa-abdulridha/wp-github-repos/master/1.png)](https://raw.githubusercontent.com/Alaa-abdulridha/wp-github-repos/master/1.png)
[![](https://raw.githubusercontent.com/Alaa-abdulridha/wp-github-repos/master/2.png)](https://raw.githubusercontent.com/Alaa-abdulridha/wp-github-repos/master/2.png)
[![](https://raw.githubusercontent.com/Alaa-abdulridha/wp-github-repos/master/3.png)](https://raw.githubusercontent.com/Alaa-abdulridha/wp-github-repos/master/3.png)


