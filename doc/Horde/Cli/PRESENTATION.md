# Presentation Layer

**New in Horde_Cli 3.0.0 beta2**

The presentation layer provides modern CLI output with three formats:
- **Unicode**: Modern emoji and symbols (✅, ⚠️, 🔍, 📊)
- **Classic**: Colored brackets `[  OK  ]` (backward compatible)
- **CI**: GitHub Actions and plain CI labels `[OK]`, `::notice::`

## Quick Start

```php
<?php
// Modern PSR-4 usage (recommended)
use Horde\Cli\Cli;

$cli = new Cli();

// Auto-detect best format (CI, Unicode, or Classic)
$presenter = $cli->getPresenter();

// Basic output levels
$presenter->ok('Task completed successfully!');
$presenter->warn('Deprecated method detected');
$presenter->info('Processing 142 files...');
$presenter->error('Configuration not found');

// Semantic categories for rich context
$presenter->semantic('detected', 'Found PHPUnit configuration');
$presenter->semantic('running', 'Executing test suite');
$presenter->semantic('created', 'Generated report.pdf');
$presenter->semantic('validation', 'Schema check failed');
```

### Upgrading from Legacy Horde_Cli

If you're using the legacy `Horde_Cli` class, the presentation layer works there too:

```php
<?php
// Legacy PSR-0 usage (still supported)
$cli = new Horde_Cli();
$presenter = $cli->getPresenter();  // Same API as modern version

$presenter->ok('Works with legacy class!');
```

**Migration tip:** Consider migrating to `Horde\Cli\Cli` for better type safety and modern PHP features.

## Supported Formats

### Unicode Presenter

Modern CLI output with emoji and Unicode symbols. Best for:
- Interactive terminals (iTerm2, Alacritty, Kitty)
- UTF-8 locales
- Modern developer tools

```php
use Horde\Cli\Cli;

$cli = new Cli();
$presenter = $cli->getPresenter(['cli_format' => 'unicode']);

$presenter->ok('Success!');              // ✅ Success!
$presenter->semantic('created', 'File'); // 📝 File
$presenter->semantic('push', 'Deploy');  // 📤 Deploy
```

### Classic Presenter

Backward-compatible colored brackets. Best for:
- Legacy environments
- PEAR-style output
- Consistent with existing Horde tools

```php
use Horde\Cli\Cli;

$cli = new Cli();
$presenter = $cli->getPresenter(['cli_format' => 'classic']);

$presenter->ok('Success!');   // [   OK   ] Success! (green)
$presenter->warn('Warning!'); // [  WARN  ] Warning! (yellow)
```

### CI Presenter

Optimized for continuous integration. Best for:
- GitHub Actions (workflow commands)
- GitLab CI, Jenkins (plain labels)
- No ANSI colors (clean logs)

```php
use Horde\Cli\Cli;

$cli = new Cli();
$presenter = $cli->getPresenter(['cli_format' => 'ci']);

// GitHub Actions format:
$presenter->ok('Success!');    // ::notice::Success!
$presenter->error('Failed!');  // ::error::Failed!

// Plain CI format (other CI systems):
// [OK] Success!
// [ERROR] Failed!
```

## Semantic Categories

The presentation layer supports 18+ semantic categories for rich context:

| Category | Meaning | Unicode | Classic | CI |
|----------|---------|---------|---------|-----|
| `ok` | Success | ✅ | [   OK   ] | [OK] |
| `warn` | Warning | ⚠️ | [  WARN  ] | [WARN] |
| `info` | Information | ℹ️ | [  INFO  ] | [INFO] |
| `error` | Error | ❌ | [ ERROR! ] | [ERROR] |
| `detected` | Found tool/resource | 🔍 | [  INFO  ] | [DETECT] |
| `running` | Process executing | ▶️ | [  INFO  ] | [RUNNING] |
| `metrics` | Statistics | 📊 | [  INFO  ] | [METRICS] |
| `regression` | Quality decreased | 📉 | [ ERROR! ] | [REGRESS] |
| `improvement` | Quality increased | 📈 | [   OK   ] | [IMPROVE] |
| `skip` | Task skipped | ⏭️ | [  INFO  ] | [SKIP] |
| `auto` | Automatic action | 🤖 | [   OK   ] | [AUTO] |
| `created` | File created | 📝 | [   OK   ] | [CREATE] |
| `updated` | File updated | 🔄 | [  INFO  ] | [UPDATE] |
| `deleted` | File deleted | 🗑️ | [ ERROR! ] | [DELETE] |
| `validation` | Validation failed | ✗ | [ ERROR! ] | [VALIDAT] |
| `config` | Config changed | ⚙️ | [  INFO  ] | [CONFIG] |
| `release` | Release created | 📦 | [  INFO  ] | [RELEASE] |
| `branch` | Git branch | 🌿 | [  INFO  ] | [BRANCH] |
| `push` | Git push | 📤 | [  INFO  ] | [PUSH] |
| `pull` | Git pull | 📥 | [  INFO  ] | [PULL] |
| `commit` | Git commit | 💬 | [  INFO  ] | [COMMIT] |
| `tag` | Git tag | 🏷️ | [  INFO  ] | [TAG] |

## Format Selection

### Auto-detect (Recommended)

```php
// Automatically selects best format based on environment
$presenter = $cli->getPresenter();
```

**Detection logic:**
1. If `GITHUB_ACTIONS` or `CI` env var → **CI format**
2. If UTF-8 locale + modern terminal → **Unicode format**
3. Otherwise → **Classic format**

### Explicit Format

```php
// Force specific format
$presenter = $cli->getPresenter(['cli_format' => 'unicode']);
$presenter = $cli->getPresenter(['cli_format' => 'classic']);
$presenter = $cli->getPresenter(['cli_format' => 'ci']);
```

### Disable Colors

```php
// Keep structure but remove colors
$presenter = $cli->getPresenter(['nocolor' => true]);
```

## Usage Examples

### Basic Application

```php
<?php
use Horde\Cli\Cli;

$cli = Cli::init();
$presenter = $cli->getPresenter();

$presenter->info('Starting backup process...');

try {
    $files = scanBackupDirectory();
    $presenter->semantic('detected', "Found {$files} files");

    $result = createArchive($files);
    $presenter->semantic('created', "Created {$result['archive']}");

    $presenter->ok('Backup completed successfully!');
} catch (Exception $e) {
    $presenter->error('Backup failed: ' . $e->getMessage());
    exit(1);
}
```

### Horde_Cli_Modular Integration

```php
<?php
use Horde\Cli\Output\Presenter;

class MyModule extends Horde_Cli_Modular_Module
{
    public function handle()
    {
        // Get presenter from injector
        $presenter = $this->_dependencies->getInstance(Presenter::class);

        $presenter->info('Processing tasks...');
        $presenter->semantic('created', 'Generated report');
        $presenter->ok('All tasks completed!');
    }
}

// Register presenter in DI container
use Horde\Cli\Cli;

$cli = new Cli();
$injector->setInstance(Presenter::class, $cli->getPresenter());
```

### Horde_Cli_Application Integration

```php
<?php
use Horde\Cli\Application;

class MyApp extends Application
{
    protected function _doRun()
    {
        // Automatically available via proxy pattern
        $presenter = $this->getPresenter();

        $presenter->info('Application started');
        $presenter->semantic('config', 'Loaded configuration');
        $presenter->ok('Ready!');
    }
}
```

## Migration from message()

The presentation layer provides a more expressive alternative to the traditional `message()` method.

**Legacy approach (still works):**
```php
$cli = new Horde_Cli();
$cli->message('Success!', 'cli.success');
$cli->message('Warning!', 'cli.warning');
$cli->message('Error!', 'cli.error');
```

**Modern approach (recommended):**
```php
use Horde\Cli\Cli;

$cli = new Cli();
$presenter = $cli->getPresenter();

$presenter->ok('Success!');
$presenter->warn('Warning!');
$presenter->error('Error!');
```

**Benefits of the modern approach:**
- Semantic categories (created, detected, validation, etc.)
- Auto-adapts to environment (CI, Unicode terminals)
- More expressive output with emoji and symbols
- Type-safe interface
- Backward compatible (old API still works)

## Compatibility

- **PHP:** 8.2+
- **Works with:** Both `Horde_Cli` (legacy PSR-0) and `Horde\Cli\Cli` (modern PSR-4)
- **Recommended:** Use `Horde\Cli\Cli` for new projects
- **Terminals:** All modern terminals (iTerm2, Alacritty, Kitty, Hyper, Windows Terminal)
- **CI Systems:** GitHub Actions, GitLab CI, Jenkins, CircleCI, Travis CI
- **Backward compatible:** No breaking changes to existing Horde_Cli API

## See Also

- Example script: `doc/Horde/Cli/presenter.php`
- Modern color demo: `doc/Horde/Cli/colors_modern.php`
- Presenter interface: `src/Output/Presenter.php`
- Factory: `src/Output/PresenterFactory.php`
- Tests: `test/Unit/Output/Presenter/`
