// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('ll');

	tinymce.create('tinymce.plugins.ll', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('mce_ll', function() {
				ed.windowManager.open({
					file : url + '/window.php',
					width : 360 + ed.getLang('ll.delta_width', 0),
					height : 210 + ed.getLang('ll.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('ll', {
				title : 'Somethign about limelight',
				cmd : 'mce_ll',
				image : url + '/ll.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('ll', n.nodeName == 'IMG');
			});
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'll',
					author 	  : 'Alex Rabe',
					authorurl : 'http://alexrabe.boelinger.com',
					infourl   : 'http://alexrabe.boelinger.com',
					version   : "2.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('ll', tinymce.plugins.ll);
})();


