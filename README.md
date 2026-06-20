# 🚀 Laragon Dashboard — RTL, Dark/Light & Bilingual

جایگزینی برای صفحه‌ی پیش‌فرض Laragon؛ یک داشبورد دوزبانه (فارسی/انگلیسی) و راست‌چین که پوشه‌های پروژه رو خودکار شناسایی می‌کنه، نوع فریم‌ورک هر کدوم رو نشون می‌ده و بین تم لایت و دارک سوییچ می‌کنه.

[![preview](https://github.com/MiRALiOG/laragon-dark-dashboard/raw/main/screenshot.png)](/MiRALiOG/laragon-dark-dashboard/blob/main/screenshot.png)

## ✨ ویژگی‌ها

- راست‌چین کامل (`dir="rtl"`) با فونت Vazirmatn برای فارسی و Inter برای انگلیسی
- دوزبانه: یک دکمه برای سوییچ بین فارسی و انگلیسی، بدون رفرش صفحه
- تاگل تم لایت/دارک، با ذخیره‌ی انتخاب کاربر (زبان و تم هر دو با `localStorage` به خاطر سپرده می‌شن)
- اسکن خودکار پوشه‌های داخل `www` و نمایش به‌صورت کارت
- تشخیص نوع پروژه از روی فایل‌های مشخصه (Laravel، WordPress، Symfony، Angular، Next.js، Vite، Node.js، Composer)
- جستجوی زنده‌ی پروژه‌ها بدون رفرش صفحه
- دکمه‌ی مستقیم برای مشاهده‌ی این پروژه روی گیت‌هاب
- دسترسی به `phpinfo()` فقط از طریق localhost (برای جلوگیری از افشای اطلاعات حساس)
- دسترسی سریع به phpMyAdmin

## 📦 نصب

1. فایل `index.php` رو دانلود کن.
2. توی مسیر ریشه‌ی Laragon (معمولاً `C:\laragon\www`) همون فایل `index.php` پیش‌فرض رو با این فایل جایگزین کن (یه بکاپ از فایل قبلی بگیر، محض احتیاط).
3. Laragon رو اجرا کن و آدرس `http://localhost` رو توی مرورگر باز کن.

همین! نیازی به نصب پکیج یا کانفیگ اضافه نیست، فقط PHP خام و بدون وابستگی خارجیه.

## ⚠️ نکته‌ی امنیتی

دسترسی به `?q=info` (که `phpinfo()` رو نشون می‌ده) به‌صورت پیش‌فرض فقط از `127.0.0.1` مجازه. اگه این پروژه رو روی یه سرور واقعی یا شبکه‌ی اشتراکی دیپلوی کردی، پیشنهاد می‌شه این بخش رو کلاً حذف کنی یا با احراز هویت محدودش کنی.

## 🎨 سفارشی‌سازی

- **پوشه‌های مخفی:** آرایه‌ی `$hiddenFolders` توی تابع `getProjectFolders()` رو ویرایش کن تا پوشه‌های دلخواهت از لیست کنار گذاشته بشن.
- **تشخیص نوع پروژه:** آرایه‌ی `$checks` توی تابع `detectProjectType()` رو می‌تونی گسترش بدی تا فریم‌ورک‌های بیشتری شناسایی بشن.
- **رنگ‌بندی:** رنگ‌های هر دو تم (لایت و دارک) به‌صورت CSS Variable توی بخش `:root` و `body.light` تعریف شدن؛ همون متغیرها رو تغییر بده تا کل پالت رنگی عوض بشه.
- **زبان پیش‌فرض:** اگه می‌خوای پیش‌فرض صفحه انگلیسی باز بشه، مقدار اولیه‌ی `savedLang` توی اسکریپت پایین صفحه رو از `'fa'` به `'en'` تغییر بده.

## 🤝 مشارکت

اگه باگی پیدا کردی یا ایده‌ای برای بهبود داری، یه Issue یا Pull Request باز کن. خوشحال می‌شم همکاری کنم.

## 📄 لایسنس

این پروژه تحت لایسنس [MIT](LICENSE) منتشر شده؛ آزادانه استفاده، تغییر و توزیع کن.

---

# 🚀 Laragon Dashboard — RTL, Dark/Light & Bilingual (English)

A drop-in replacement for Laragon's default landing page: a bilingual (Persian/English), right-to-left dashboard that auto-detects your local project folders, identifies each project's framework, and switches between light and dark themes.

## ✨ Features

- Full RTL layout (`dir="rtl"`) with the Vazirmatn font for Persian and Inter for English
- Bilingual: one button switches between Persian and English instantly, no page reload
- Light/dark theme toggle, with the user's choice remembered (both language and theme are saved via `localStorage`)
- Automatic scan of folders inside `www`, rendered as cards
- Project type detection from marker files (Laravel, WordPress, Symfony, Angular, Next.js, Vite, Node.js, Composer)
- Live search filter, no page reload
- A direct button to view this project on GitHub
- `phpinfo()` is restricted to localhost requests only, to avoid leaking sensitive server info
- Quick access to phpMyAdmin

## 📦 Installation

1. Download `index.php`.
2. Replace the default `index.php` in your Laragon root (usually `C:\laragon\www`) with this file — back up the original first, just in case.
3. Start Laragon and open `http://localhost` in your browser.

No dependencies, no build step — just plain PHP.

## ⚠️ Security note

The `?q=info` route (which exposes `phpinfo()`) only works when requested from `127.0.0.1` by default. If you ever deploy this on a real server or shared network, remove that route entirely or put it behind authentication.

## 🎨 Customization

- **Hidden folders:** edit the `$hiddenFolders` array inside `getProjectFolders()` to exclude folders you don't want listed.
- **Project detection:** extend the `$checks` array inside `detectProjectType()` to recognize more frameworks.
- **Colors:** both theme palettes are defined as CSS variables under `:root` and `body.light` — change those variables to restyle the whole dashboard.
- **Default language:** to open the page in English by default, change the initial `savedLang` value in the script at the bottom of the page from `'fa'` to `'en'`.

## 🤝 Contributing

Found a bug or have an idea? Feel free to open an issue or pull request.

## 📄 License

Released under the [MIT License](LICENSE) — use, modify, and share freely.
