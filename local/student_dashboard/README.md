# Custom Student Dashboard for Moodle

A modern, responsive custom dashboard designed specifically for students in Moodle. This plugin provides an enhanced user experience with a clean, intuitive interface that displays key student information, course progress, and recent activities.

## Features

- **Modern UI Design**: Clean, responsive interface with gradient backgrounds and modern card layouts
- **Student-Only Dashboard**: Automatically detects students and redirects them to the custom dashboard
- **Course Progress Tracking**: Displays enrolled, completed, and incomplete course statistics
- **Recent Activities**: Shows recently accessed courses and materials
- **Badge System**: Visual representation of student achievements
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **URL Preservation**: Maintains the standard `/my` URL structure

## Screenshots

The dashboard includes:
- Header section with welcome message and user avatar
- Information bar with dismissible notifications
- User profile card with avatar and quick stats
- Course enrollment statistics with colored badges
- Achievement badges display
- Recently accessed items grid
- Responsive design for all screen sizes

## Installation

### Method 1: Custom Scripts (Recommended)

1. Extract the plugin to your Moodle directory:
   ```
   /path/to/moodle/local/student_dashboard/
   ```

2. Add the following line to your `config.php` file before the `require_once` line:
   ```php
   $CFG->customscripts = __DIR__ . '/local/student_dashboard/customscripts';
   ```

3. Log in as an administrator and visit the notifications page to complete the installation.

### Method 2: Manual Installation

1. Extract the plugin to your Moodle directory:
   ```
   /path/to/moodle/local/student_dashboard/
   ```

2. Log in as an administrator and visit the notifications page to complete the installation.

3. For this method, students will be redirected to `/local/student_dashboard/my/index.php` instead of preserving the `/my` URL.

## Configuration

No additional configuration is required. The plugin automatically:
- Detects users with the 'student' role
- Redirects them to the custom dashboard
- Maintains the original dashboard for non-student users

## User Role Detection

The plugin identifies students by checking for users assigned the 'student' role. Users without this role will continue to see the default Moodle dashboard.

## File Structure

```
local/student_dashboard/
├── classes/
│   └── observer.php              # Event observer for login handling
├── customscripts/
│   └── my/
│       └── index.php            # Custom script to override /my URL
├── db/
│   └── events.php               # Event observer definitions
├── lang/
│   └── en/
│       └── local_student_dashboard.php  # English language strings
├── my/
│   └── index.php                # Custom student dashboard page
├── lib.php                      # Plugin library functions
├── styles.css                   # Custom CSS styles
├── version.php                  # Plugin version information
└── README.md                    # This file
```

## Customization

### Styling
The dashboard appearance can be customized by editing `styles.css`. The design uses:
- CSS Grid and Flexbox for responsive layouts
- CSS Custom Properties for easy color theming
- Modern gradient backgrounds
- Hover effects and transitions

### Language Strings
All text can be customized through the language files in `lang/en/local_student_dashboard.php`. Additional language packs can be added by creating corresponding language directories.

### Content
The dashboard content can be modified by editing `my/index.php`. The page includes:
- Course enrollment data
- Progress statistics
- Recent activity tracking
- Badge system integration

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Internet Explorer is not supported

## Requirements

- Moodle 3.9 or higher
- PHP 7.4 or higher
- Modern web browser with CSS Grid support

## Troubleshooting

### Dashboard Not Loading
1. Ensure the plugin is properly installed in `/local/student_dashboard/`
2. Check that the user has the 'student' role assigned
3. Verify the `$CFG->customscripts` setting in config.php (if using Method 1)

### Styling Issues
1. Clear your browser cache
2. Check that `styles.css` is accessible
3. Verify CSS is loading in browser developer tools

### Permission Errors
1. Ensure proper file permissions on the plugin directory
2. Check that the web server can read all plugin files

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

## Support

For issues, questions, or contributions, please refer to your Moodle administrator or the plugin documentation.

## Changelog

### Version 1.0.0
- Initial release
- Custom student dashboard with modern UI
- Automatic student detection and redirection
- Responsive design implementation
- Course progress tracking
- Recent activities display