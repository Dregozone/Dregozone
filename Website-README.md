# Professional Developer Portfolio Website

A modern, professional portfolio website built with Laravel 12 and Livewire, designed to showcase web development skills and attract potential clients.

## Features

### 🏠 **Home Page**
- Stunning hero section with animated background elements
- Featured projects showcase
- Recent blog posts display
- Prominent newsletter signup
- Services section highlighting expertise
- Call-to-action sections

### 📝 **Blog System**
- Full blog with search and filtering capabilities
- Tag-based categorization
- Individual blog post pages with related posts
- View tracking and analytics
- Admin interface for content management

### 💼 **Project Showcase**
- Featured projects with technology tags
- Project status tracking (completed, in progress, archived)
- GitHub integration
- Live demo links

### 📧 **Contact & Work Requests**
- Professional contact form
- Work request submission with budget and timeline fields
- Multiple inquiry types (general, work request, partnership)
- Form validation and success messages

### 📬 **Newsletter System**
- Email subscription management
- Prominent signup forms throughout the site
- Success state handling
- GDPR-compliant messaging

### 🔧 **Admin Interface**
- Secure admin-only access
- Blog post management (create, edit, delete, publish)
- Content preview and status management
- User-friendly interface

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Livewire 3, Alpine.js
- **Styling**: Tailwind CSS
- **Database**: SQLite (development), MySQL/PostgreSQL (production)
- **Authentication**: Laravel Breeze
- **File Uploads**: Laravel Storage

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd dregozone
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## Usage

### Public Pages
- **Home**: `/` - Main landing page
- **Blog**: `/blog` - Blog listing with search and filters
- **Blog Post**: `/blog/{slug}` - Individual blog post
- **Contact**: `/contact` - Contact form and work requests

### Admin Pages (Authentication Required)
- **Blog Management**: `/admin/blog` - Manage blog posts
- **Create Post**: `/admin/blog/create` - Create new blog post
- **Edit Post**: `/admin/blog/{id}/edit` - Edit existing post

### Authentication
- **Login**: `/login`
- **Register**: `/register`
- **Dashboard**: `/dashboard` (after login)

## Customization

### Content Management
1. **Blog Posts**: Use the admin interface at `/admin/blog`
2. **Projects**: Edit `database/seeders/ProjectSeeder.php` and re-run seeds
3. **Newsletter**: Manage subscribers through the database
4. **Contact Messages**: View submissions in the database

### Styling
- Modify Tailwind classes in the Blade templates
- Update color schemes in `resources/css/app.css`
- Customize components in `resources/views/livewire/`

### Configuration
- Update site information in `config/app.php`
- Modify email settings in `config/mail.php`
- Configure file uploads in `config/filesystems.php`

## Database Schema

### Blog Posts
- `title`, `slug`, `excerpt`, `content`
- `featured_image`, `tags` (JSON)
- `status` (draft/published), `published_at`
- `views` counter

### Projects
- `title`, `description`, `image`
- `url`, `github_url`, `technologies` (JSON)
- `status` (in_progress/completed/archived)
- `featured`, `order`

### Newsletter Subscribers
- `email`, `name`, `is_active`
- `subscribed_at`, `unsubscribed_at`

### Contact Messages
- `name`, `email`, `subject`, `message`
- `type` (work_request/general/partnership)
- `status` (new/read/replied/archived)
- `metadata` (JSON for additional fields)

## Deployment

### Production Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- Web server (Apache/Nginx)
- Database (MySQL/PostgreSQL)

### Deployment Steps
1. Set up production environment
2. Configure `.env` for production
3. Run `composer install --optimize-autoloader --no-dev`
4. Run `npm run build`
5. Set up database and run migrations
6. Configure web server
7. Set up SSL certificate
8. Configure file uploads and storage

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support or questions, please contact the developer or create an issue in the repository.

---

**Built with ❤️ using Laravel and Livewire** 