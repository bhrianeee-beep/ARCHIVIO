# ARCHIVIO – Technical Summary

## 1. Project Overview
ARCHIVIO is a web-based Archive Management System developed using PHP, MySQL, HTML, and CSS. It allows users to upload, view, search, filter, edit, and delete digital documents, making file management more organized and convenient.

## 2. Database Design
ARCHIVIO uses two MySQL tables: `categories` and `archives`. The categories table stores document classifications, while the archives table contains file details such as title, file path, file size, category ID, and upload date. A foreign key connects the tables, allowing one category to contain multiple archived documents.

## 3. Data Structure and Algorithm
The system uses a **Queue Data Structure** that follows the First-In, First-Out (FIFO) principle. The first file added is the first processed. The `enqueue()` operation adds files, while `dequeue()` removes the first file for processing. The `isEmpty()` and `size()` operations check whether the queue is empty and count its items. After processing, file information is saved in MySQL.

## 4. Time and Space Complexity

| Operation | Time Complexity |
|---|---|
| Enqueue | O(1) amortized |
| Dequeue | O(n) |
| isEmpty / size | O(1) |

The queue requires **O(n) space** to store waiting files. Processing n files has a worst-case total queue-operation time of **O(n²)** because the current implementation uses `array_shift()`.

## 5. Security Measures
The system uses PDO prepared statements to help prevent SQL injection, input validation to check submitted data, and CSRF tokens to protect edit and delete actions. Uploaded documents are stored in the designated uploads folder.

## 6. Conclusion
ARCHIVIO combines a FIFO Queue and MySQL database to manage digital documents. It demonstrates how data structures and database systems work together in a practical web application.