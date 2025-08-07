
📸 Rep Effect Plugin
===================

The **Rep Effect Plugin** lets you create an engaging image hover effect with GSAP animation. 
You can upload multiple images and generate individual shortcodes for each, which can be used on any page or post — including with page builders like Elementor, WPBakery, Beaver Builder, and more.

🔧 Features
-----------

- Upload and manage multiple images in a simple admin UI.
- Each image gets a unique shortcode.
- Lightweight GSAP animation for a repeat image hover effect.
- Works with all major page builders.

🚀 Installation
---------------

1. Download the plugin folder and upload it to `wp-content/plugins/`.
2. Activate the plugin via **Plugins > Installed Plugins** in your WordPress dashboard.
3. Go to **Settings > Rep Effect Images** to start uploading images.

🖼️ How to Add Images
---------------------

1. Navigate to **Settings > Rep Effect Images**.
2. Click the **Add Image** button.
3. Upload or select an image from the media library.
4. Copy the generated **shortcode** shown below each image block, like:
   `[rep_effect id="0"]`

💡 How to Use the Shortcode
----------------------------

✅ Using in Classic Editor or Gutenberg:
- Simply paste the shortcode (e.g. `[rep_effect id="0"]`) into the editor where you want the image effect to appear.

✅ Using in Elementor:
1. Drag and drop the **Shortcode** widget onto your page.
2. Paste your shortcode:
   `[rep_effect id="0"]`
3. Preview or publish the page.

✅ Using in Any Page Builder:
- Most page builders (WPBakery, Brizy, etc.) offer a **shortcode** element/module.
- Drop that in, paste the shortcode, and you’re done.

⚙️ Shortcode Options
---------------------

| Attribute | Description                    | Example                |
|-----------|--------------------------------|------------------------|
| `id`      | ID/index of the uploaded image | `[rep_effect id="2"]` |

> **Note:** The ID corresponds to the order of images added on the plugin settings page.

📂 Plugin Structure
--------------------

```
rep-effect/
├── css/
│   └── main-rep.css
├── js/
│   └── rep.js
├── rep-effect.php  ← main plugin file
```

🧼 Uninstalling
----------------

Simply deactivate and delete the plugin. No data is stored outside of the `rep_effect_images` option.

🛠️ Troubleshooting
-------------------

- **Image not showing?** Make sure the image URL is valid and saved properly.
- **Animation not working?** Ensure JavaScript is not being blocked and no other plugin is conflicting with GSAP.

🙌 Credits
-----------

- GSAP: https://greensock.com/gsap/
- WordPress Plugin API
- Codrops sponsor ad script

📜 License
-----------

This plugin is licensed under the GPLv2 or later: https://www.gnu.org/licenses/gpl-2.0.html
