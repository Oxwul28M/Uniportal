# 🎓 UniPortal — Technical Documentation

## 1. Introduction
UniPortal is an academic and financial management platform designed for universities. The system centralizes enrollment control, grading, multi-currency payments, and student support through an internal virtual assistant.

---

## 2. Technical Architecture
- **Framework:** Laravel 12 (PHP 8.2+)
- **Database:** PostgreSQL (Hosted on Supabase)
- **Frontend:** Tailwind CSS + Alpine.js + Material Symbols
- **Asset Bundling:** Vite
- **Financial Engine:** Dual-currency system with real-time exchange rate integration via external APIs.

---

## 3. Roles and Permissions

| Role | Capabilities |
| :--- | :--- |
| **Admin** | Full system control: user management, global fee structures, news broadcasting, security settings, and global financial reporting. |
| **Manager** | Operational control: notice approval, student payment validation, and revenue report exportation. |
| **Teacher** | Academic management: assigned course management, bulk grading (manual or Excel), and academic agenda control. |
| **Student** | Academic tracking: grade visualization, payment reporting, document downloads, and chatbot interaction. |

---

## 4. Core Modules

### 💰 Financial System 
- **Standardization:** The system uses a primary Reference unit (REF) for internal accounting.
- **Exchange Rates:** Integration with external APIs to fetch daily official rates. It also allows manual overrides from the Admin/Manager panel.
- **Payment Flow:** Students report payments in local currency by specifying the transaction date. The system automatically fetches the historical exchange rate for **that specific exact date** to accurately calculate the REF equivalent.

### 🤖 Internal Academic Chatbot
- **Engine:** Keyword-based Intent Matching system entirely built in-house.
- **Localization:** Highly optimized for regional vocabulary, common spelling mistakes, and specific financial terms.
- **Privacy & Cost:** 100% locally hosted. It does not rely on costly external LLM APIs, guaranteeing fast response times and zero operational costs.
- **Capabilities:** Instant queries regarding account balances, enrolled subjects, and historical grades.

### 📚 Academic Management
- **Bulk Uploads:** Teachers can download Excel templates, input grades offline, and seamlessly re-import them into the system.
- **Dynamic Agenda:** A synchronized event system where teachers create events and students view their unified academic schedule.

---

## 5. Security and Best Practices
- **File Validation:** Strict MIME type and size validation for payment receipts to ensure only secure image formats are uploaded.
- **Active Middleware:** Real-time session invalidation. Suspended or rejected users are automatically logged out of the system.
- **Data Integrity (Soft Deletes):** Physical deletion of users is restricted. A soft-block approach (via a `suspended` status) is implemented to preserve historical university data and academic records.

---
✨ *Developed with a focus on enterprise robustness and modern user experience.*
