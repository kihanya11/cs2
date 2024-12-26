# Define environment variables
PHP_BIN = php
COMPOSER_BIN = composer
GIT_BIN = git
NPM_BIN = npm
XAMPP_DIR = "C:/xampp" # Adjust if your XAMPP is installed in a different directory
PHP_INI = C:\php\php.ini

# Install XAMPP (if not installed)
INSTALL_XAMPP:
	@echo "Installing XAMPP..."
	@echo "Please download and install XAMPP from https://www.apachefriends.org/download.html"
	@echo "Ensure that XAMPP is installed and PHP is configured in the XAMPP environment."

# Install PHP extensions if not installed
INSTALL_ZIP_EXTENSION:
	@echo "Enabling PHP zip extension..."
	@powershell -Command "(Get-Content $(PHP_INI)) -replace ';extension=zip', 'extension=zip' | Set-Content $(PHP_INI)"
	@echo "Zip extension enabled in php.ini."

INSTALL_FILEINFO_EXTENSION:
	@echo "Enabling PHP fileinfo extension..."
	@powershell -Command "(Get-Content $(PHP_INI)) -replace ';extension=fileinfo', 'extension=fileinfo' | Set-Content $(PHP_INI)"
	@echo "Fileinfo extension enabled in php.ini."

INSTALL_CURL_EXTENSION:
	@echo "Enabling PHP curl extension..."
	@powershell -Command "(Get-Content $(PHP_INI)) -replace ';extension=curl', 'extension=curl' | Set-Content $(PHP_INI)"
	@echo "Curl extension enabled in php.ini."

# Install Composer
INSTALL_COMPOSER:
	@echo "Installing Composer..."
	@curl -sS https://getcomposer.org/installer | $(PHP_BIN)

# Install Git
INSTALL_GIT:
	@echo "Installing Git..."
	@echo "Please download and install Git from https://git-scm.com/download/win"
	@echo "Ensure Git is added to your PATH during installation."

# Install npm (Node.js package manager)
INSTALL_NPM:
	@echo "Installing npm..."
	@echo "Please install Node.js (which includes npm) from https://nodejs.org/en/download/"
	@echo "Ensure npm is added to your PATH during installation."

# Install project dependencies using Composer
INSTALL_PHP_DEPENDENCIES:
	@echo "Installing PHP dependencies using Composer..."
	$(COMPOSER_BIN) install

# Update voku package to address deprecation warning
UPDATE_VOKU:
	@echo "Updating voku/portable-ascii package to resolve deprecation warning..."
	$(COMPOSER_BIN) update voku/portable-ascii

# Install Livewire package using Composer
INSTALL_LIVEWIRE:
	@echo "Installing Livewire package using Composer..."
	$(COMPOSER_BIN) require livewire/livewire

# Install JavaScript dependencies using npm and run the build process
INSTALL_JS_DEPENDENCIES:
	@echo "Installing JavaScript dependencies using npm..."
	$(NPM_BIN) install
	$(NPM_BIN) run dev

# Install everything (PHP + JS dependencies)
INSTALL: INSTALL_XAMPP INSTALL_ZIP_EXTENSION INSTALL_FILEINFO_EXTENSION INSTALL_CURL_EXTENSION INSTALL_COMPOSER INSTALL_GIT INSTALL_NPM INSTALL_PHP_DEPENDENCIES INSTALL_LIVEWIRE INSTALL_JS_DEPENDENCIES

# Run the PHP server using XAMPP (Apache)
RUN_XAMPP_SERVER:
	@echo "Starting Apache server using XAMPP..."
	@$(XAMPP_DIR)/xampp_start.exe apache
	@$(XAMPP_DIR)/xampp_start.exe mysql
	@echo "Apache and MySQL servers are now running."

# Stop the XAMPP servers (Apache and MySQL)
STOP_XAMPP_SERVER:
	@echo "Stopping Apache and MySQL servers using XAMPP..."
	@$(XAMPP_DIR)/xampp_stop.exe apache
	@$(XAMPP_DIR)/xampp_stop.exe mysql
	@echo "Apache and MySQL servers have been stopped."

# Run the PHP development server (alternative to XAMPP)
RUN_SERVER:
	@echo "Starting PHP development server..."
	$(PHP_BIN) artisan serve

# Clean the vendor directory and node_modules
CLEAN:
	@echo "Cleaning vendor and node_modules directories..."
	rm -rf vendor/ node_modules/

# Help command
help:
	@echo "Makefile for PHP and JavaScript project dependencies with XAMPP"
	@echo "Available targets:"
	@echo "  INSTALL: Installs all required extensions, Composer, Git, npm, XAMPP, and project dependencies (both PHP and JS)"
	@echo "  INSTALL_PHP_DEPENDENCIES: Installs PHP dependencies using Composer"
	@echo "  UPDATE_VOKU: Updates the voku/portable-ascii package to resolve deprecation warning"
	@echo "  INSTALL_JS_DEPENDENCIES: Installs JavaScript dependencies using npm and runs dev build"
	@echo "  INSTALL_LIVEWIRE: Installs Livewire package using Composer"
	@echo "  INSTALL_XAMPP: Installs XAMPP (if not installed)"
	@echo "  RUN_XAMPP_SERVER: Starts Apache and MySQL servers using XAMPP"
	@echo "  STOP_XAMPP_SERVER: Stops Apache and MySQL servers using XAMPP"
	@echo "  RUN_SERVER: Starts the PHP development server"
	@echo "  CLEAN: Removes the vendor and node_modules directories"
	@echo "  help: Displays this help message"
