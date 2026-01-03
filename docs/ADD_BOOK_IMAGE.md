Add the book cover image for "Dari Balik Penjara dan Pengasingan"

Where to place the attached image file:

1. Save the attached image (from the code workspace attachments) with the filename:

   storage/app/public/book-covers/dari-balik-penjara.jpg

2. If the `storage` symbolic link is not created yet (so the files are accessible via `asset('storage/...')`), run:

   # In PowerShell (Windows)
   php artisan storage:link

   # or in bash
   php artisan storage:link

3. Verify the file is accessible in browser (after starting the dev server):

   http://localhost:8000/storage/book-covers/dari-balik-penjara.jpg

Running the seeder:

You can run the seeder we added with the fully-qualified class name. From the project root run:

   # In PowerShell (Windows)
   php artisan db:seed --class=\Database\Seeders\AddDariBalikPenjaraSeeder

If you prefer to run all seeders (including this one), run:

   php artisan db:seed

Notes:
- The seeder will check for duplicate titles and skip creation if a book with the same title already exists.
- If you want the cover image to be placed elsewhere, update the `image` path in the seeder (`database/seeders/AddDariBalikPenjaraSeeder.php`) to match your chosen location.
