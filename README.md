# ARCHIVIO
### Archive Management Web Application

## Project Overview

ARCHIVIO is a web-based archive management system developed using PHP and MySQL. It allows users to upload, organize, search, view, edit, and delete digital documents. The system helps keep files organized through categories and provides an easy way to manage archived documents.

## Technologies Used

- PHP – Server-side programming
- MySQL – Database management
- PDO – Secure database interactions
- HTML – Web page structure
- CSS – User interface design
- XAMPP – Local development environment

## Main Features

1. **Dashboard** – Displays archive statistics and recent uploads.
2. **File Upload** – Allows users to upload one or multiple files.
3. **Archive Management** – Displays uploaded documents in a table.
4. **Search and Filter** – Finds documents using keywords and categories.
5. **Edit Archives** – Allows users to update document titles and categories.
6. **Delete Archives** – Removes archive records and their stored files.
7. **Category Management View** – Displays available categories and the number of archived documents in each category.

## Database Structure

ARCHIVIO uses a MySQL database named `archivio_db` with two related tables:

**1. categories**
- `category_id` – Primary key
- `category_name` – Name of the category

**2. archives**
- `archive_id` – Primary key
- `title` – Document title
- `file_path` – Stored file location
- `file_size_kb` – File size in kilobytes
- `category_id` – Foreign key connected to the categories table
- `created_at` – Date and time of upload

The relationship is one-to-many: one category can contain multiple archived documents.

## Data Structure and Algorithm

ARCHIVIO implements a **FIFO (First-In, First-Out) Queue** for batch file processing.

When multiple files are uploaded, each valid file is added to the queue using the `enqueue()` operation. The system processes files one at a time using `dequeue()`. The first file added is the first file processed.

### Queue Operations

- `enqueue()` – Adds a file to the end of the queue.
- `dequeue()` – Removes and returns the first file in the queue.
- `isEmpty()` – Checks whether the queue has no remaining files.
- `size()` – Returns the number of files in the queue.

### Time and Space Complexity

| Operation | Time Complexity |
|---|---|
| Enqueue | O(1) amortized |
| Dequeue | O(n) |
| isEmpty | O(1) |
| size | O(1) |

The queue uses O(n) space for n queued files. Processing a batch of n files takes O(n²) total time with the current PHP array-based `array_shift()` implementation, excluding file and database operations.

## Security Measures

- PDO prepared statements for database queries
- Input validation for archive updates
- CSRF token validation for edit and delete requests
- File storage inside the project's uploads directory

## Project Structure

- `config/` – Database connection
- `controllers/` – Upload, update, and delete processes
- `dsa/` – FIFO Queue implementation
- `views/` – Application pages
- `assets/` – CSS styling
- `uploads/` – Uploaded documents
- `index.php` – Dashboard
- `schema.sql` – Database structure and sample categories

## Conclusion

ARCHIVIO combines database management and a FIFO Queue data structure to provide an organized way of handling digital archives. It demonstrates the use of PHP, MySQL, PDO, and basic data structure operations in a practical web application.