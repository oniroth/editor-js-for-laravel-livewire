<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/oniroth/editor-js-for-laravel-livewire/2f5590cba7a76ad2125af87278f2a08884163d41/logo.d2a59c1c.svg" width="100" alt="Laravel Logo"></a></p>


# Editor Js For Laravel Livewire
### description
- توضیحات

کنترلر های موجود

  FetchUrlController : برای گرفتن اطلاعات لینک
  uploadFileController : برای اپلود فایل ها

برای نمایش موارد ذخیره شده در دیتابیس بصورت زیر در آدرس مرورگر وارد کنید.
<br>
<code>
<per>URL</per>
  http://127.0.0.1:8000/e/1
</code>

کامپوننت ذخیره و نمایش ویرایشگر
<br>
<code>
  resources/views/livewire/editor-js.blade.php
</code>
  
  کامپوننت نمایش و رندر اطلاعات json
  <br>
  <code>
  resources/views/livewire/editor-js-show.blade.php
  </code>

### How To Use
- نحوه استفاده
<br/>

```bash
composer install
```
```bash
npm install
```
```bash
php artisan migrate
```
```bash
php artisan storage:link
```
```bash
composer run dev
```
## Plugins
- header 
- simple-image 
- list 
- checklist 
- quote 
- code 
- table 
- delimiter 
- embed 
- marker 
- inline-code 
- editor-js-code 
- title-editorjs 
- editorjs-columns 
- editorjs-drawing-tool 
- attaches 
- link 
- image
## Libraries
- EditorJs
- Laravel
- Livewire
- Tailwind
- DaisyUi
- AlpineJs
