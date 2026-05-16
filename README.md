# SnapResell - Online Second-Hand Goods Marketplace ♻️

SnapResell is a sustainable, customer-to-customer (C2C) e-commerce platform that connects buyers and sellers of pre-owned goods. Built with a robust Laravel backend and MySQL database, it facilitates secure transactions, real-time user communication, and a unique gamified trust system to encourage sustainable consumer habits.

## 🚀 Features

### For Buyers
* **Smart Search & Filtering:** Browse items by category (Clothes, Electronics, Vehicles, Toys, Furniture), price range, and latest additions.
* **Super Coins System:** Earn virtual currency to apply discounts on future purchases (1 Coin = 1 Rs).
* **Real-Time Communication:** Integrated live chat and call functionality to negotiate with sellers or resolve return requests.
* **Cart & Secure Checkout:** Add items to the cart and process mock payments via Card or UPI/QR.

### For Sellers
* **Seller Dashboard:** Track total active items, successful sales, return rates, and overall earnings.
* **Eco Points & Trust Score:** A dynamic trust metric that increases with successful sales and decreases with high return rates, establishing seller credibility.
* **Streamlined Inventory Management:** Easily list new items with images, descriptions, pricing, and category tags.

### Core Platform Features
* **Authentication & Role Management:** Seamless login and registration with dual user-role toggling (Buyer/Seller).
* **Transparent Review Architecture:** Product reviews are generated based on direct buyer-seller chat interactions detailing purchase and return justifications.

## 🛠️ Tech Stack

* **Backend:** Laravel (PHP)
* **Database:** MySQL
* **Frontend:** Blade Templating, HTML, CSS, JavaScript
* **Real-Time Communication:** WebSockets (via Laravel Reverb or Pusher)
* **Architecture:** MVC (Model-View-Controller)

## ⚙️ Installation and Setup

Follow these steps to run the project locally on your machine.

**Prerequisites:**
* PHP >= 8.1
* Composer
* MySQL
* Node.js & npm

**1. Clone the repository**
```bash
git clone [https://github.com/yourusername/SnapResell.git](https://github.com/yourusername/SnapResell.git)
cd SnapResell
