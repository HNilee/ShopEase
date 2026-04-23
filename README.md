<div align="center">

# 🎮 ShopEase
**Smart Shopping, Made Simple — The Premier Digital Gaming Asset Marketplace**

[![Deploy Status](https://img.shields.io/badge/Vercel-Deployed-success?style=for-the-badge&logo=vercel)](https://shopease.my.id)
[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Database](https://img.shields.io/badge/Supabase-PostgreSQL-3ECF8E?style=for-the-badge&logo=supabase)](https://supabase.com)
[![Payment Gateway](https://img.shields.io/badge/Midtrans-Payment_Gateway-00529B?style=for-the-badge)](https://midtrans.com)
[![Hosting](https://img.shields.io/badge/Vercel-Serverless_Hosting-000000?style=for-the-badge&logo=vercel)](https://vercel.com)
[![Status](https://img.shields.io/badge/Status-Active_Development-blue?style=for-the-badge)](#)

[Visit Website](https://shopease.my.id) · [Report Bug](#) · [Request Feature](#)

</div>

---

## 📖 Business Overview

**ShopEase** is a dedicated B2C e-commerce platform built to solve the most critical problem in the digital gaming community: **Trust**. 

Trading digital game assets (accounts, rare items, in-game currency) is often plagued by scams and fraudulent activities. ShopEase acts as a secure bridge between verified sellers and passionate gamers by utilizing a strict **Consignment & Escrow Model**. We hold the buyer's payment securely until the digital asset is safely delivered and verified, ensuring a zero-scam environment.

### 🎯 Value Proposition
- **For Buyers:** 100% money-back guarantee if the digital asset is not delivered as promised.
- **For Sellers:** A dedicated, targeted marketplace with guaranteed payouts for legitimate transactions.
- **For the Ecosystem:** A streamlined, automated middleman system that reduces manual verification overhead.

---

## ✨ Key Features

### 🛍️ User & Buyer Features
- **Smart Catalog:** Advanced search and filtering for game assets.
- **Dynamic Cart:** Persistent shopping cart with real-time quantity calculation.
- **Order Tracking:** Seamless dashboard to track purchase status (Pending, Escrow, Completed).
- **Responsive UI:** Dark/Light mode support with a clean, Tailwind-powered interface.

### 🛡️ Admin & Seller Management
- **Role-Based Access Control:** Distinct roles for Buyers, Sellers, and Admins.
- **Escrow Dashboard:** Admin interface to manage funds and release payments upon transaction completion.
- **Announcement System:** Global notification pop-ups controlled directly from the admin panel.

### 🔐 Enterprise-Grade Security
- **Fraud Prevention & Transaction Logs:** Immutable logs tracking IP addresses, timestamps, and status changes to prevent post-purchase disputes.
- **Data Protection Policy:** Strict adherence to privacy, ensuring sensitive KYC data is encrypted and used solely for operational security.
- **SSL/TLS & Password Hashing:** Securing data in transit and at rest using HTTPS, bcrypt, and SHA-256.

---

## 🛠️ Technical Architecture

ShopEase is built with a modern, scalable serverless architecture:

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Frontend** | Tailwind CSS + Blade + Vite | Provides a blazing-fast, responsive user interface with native Dark Mode. |
| **Backend** | Laravel 11.x (PHP 8.2) | Handles core business logic, routing, and robust authentication. |
| **Database** | Supabase (PostgreSQL) | Utilizing IPv4 Connection Pooling for high-speed, serverless-compatible data storage. |
| **Hosting** | Vercel | Serverless PHP deployment ensuring high availability and zero maintenance. |

---

## 🚀 Local Development Guide

Want to run ShopEase on your local machine? Follow these instructions.

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL database (Local or Supabase)

### Step-by-Step Installation

1. **Clone the repository**
   ```sh
   git clone [https://github.com/your-username/ShopEase.git](https://github.com/your-username/ShopEase.git)
   cd ShopEase
   ```
   
2. **Install PHP Dependencies**
   ```sh
   composer install
   ```

3. **Install Frontend Assets**
   ```sh
   npm install
   npm run build
   ```

4. **Environment Setup**
   Copy the example environment file:
   ```sh
   cp .env.example .env
   ```

5. **Generate App Key & Migrate**
   ```sh
   php artisan key:generate
   php artisan migrate --seed
   ```

6. **Serve the Application**
   ```sh
   php artisan serve
   ```
   Access the app at : http://localhost:8000

---

## ☁️ Vercel Deployment Notes
This project is optimized for Vercel. If deploying your own instance, ensure you follow these critical environment rules:
1. Remove Database Credentials from vercel.json: Use the Vercel Dashboard for all sensitive DB_* environment variables.
2. Session Handling: Set SESSION_DRIVER=cookie in your Vercel Environment Variables to prevent serverless amnesia.
3. Asset Routing: Utilize \Illuminate\Support\Facades\URL::forceScheme('https'); in your AppServiceProvider to prevent mixed-content errors.

---

## 🧑🏻‍💻 Developed By

## Arya Pannadana
1. Information System Student of Universitas Multimedia Nusantara
2. Crafted with passion for Web Development, E-Business, and Information System Project Management.

[![LinkedIn Badge](https://img.shields.io/badge/LinkedIn-Connect_with_me-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](www.linkedin.com/in/arya-pannadana-056155318/)

<br>
<i>If you find this project helpful, please leave a ⭐️!</i>
</div>

---
<div align="center">
<i>Copyright © 2026 ShopEase. All Rights Reserved.</i>
    
----
