# AGENTS.md - Agentic Coding Guidelines for SinergiaCRM

## Overview

SinergiaCRM is a SuiteCRM-based CRM system built on PHP 8.0+. This file provides guidelines for agentic coding agents working in this repository.

## Project Structure

```
SinergiaCRM/
├── lib/                    # Core library code (PSR-4: SuiteCRM\)
├── include/                # Additional includes
├── modules/                # CRM modules
├── custom/                 # Custom extensions
├── tests/                  # Test suite
│   ├── unit/phpunit/       # Unit tests
│   └── acceptance/         # Acceptance tests
├── Api/                    # REST API
└── vendor/                 # Composer dependencies
```

## Build / Lint / Test Commands

### Running Tests

**All unit tests:**
```bash
cd tests
../vendor/bin/phpunit
```

**Single test file:**
```bash
../vendor/bin/phpunit tests/unit/phpunit/lib/SuiteCRM/Utility/ArrayMapperTest.php
```

**Single test method:**
```bash
../vendor/bin/phpunit tests/unit/phpunit/lib/SuiteCRM/Utility/ArrayMapperTest.php --filter test
```

**With coverage:**
```bash
../vendor/bin/phpunit --coverage-html coverage
```

### Code Style (PHP_CodeSniffer)

```bash
# Check coding standards (PSR-2 based)
./vendor/bin/phpcs --standard=phpcs.xml <file_or_directory>

# Auto-fix issues
./vendor/bin/phpcbf <file_or_directory>
```

### Static Analysis (PHPStan)

```bash
./vendor/bin/phpstan analyse lib/ --level=5
```

### PHP CS Fixer

```bash
./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
./vendor/bin/php-cs-fixer fix --dry-run --diff <file>
```

## Code Style Guidelines

### PHP Version
- Minimum: PHP 8.0
- Use PHP 8.x features when appropriate (named arguments, attributes, etc.)

### Coding Standard
- Follow **PSR-2** (PHP Standard Recommendations)
- The project uses `phpcs.xml` configuration based on PSR-2 with some exclusions

### Imports
- Use explicit imports for classes
- Group imports by vendor (Symfony, SuiteCRM, etc.)
- Prefer short use statements for readability

```php
use SuiteCRM\Utility\ArrayMapper;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;
```

### Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| Classes | PascalCase | `ArrayMapper`, `SugarBean` |
| Methods | camelCase | `getMappings()`, `mapArray()` |
| Variables | camelCase | `$mappings`, `$cleanArray` |
| Constants | UPPER_CASE | `sugarEntry`, `DEFAULT_VALUE` |
| Files | PascalCase (classes) | `ArrayMapper.php` |
| Test Classes | PascalCase + Test | `ArrayMapperTest.php` |
| Test Methods | camelCase + test prefix | `testMapArray()` |

### Types
- Use PHP native type hints when available
- Use return types where appropriate
- Document complex PHPDoc types

```php
public function setMappings(array $mappings): ArrayMapper
public function map(?array $keys = null): array
private function getMappings(): array
```

### PHPDoc Comments

- Use for all public methods
- Include `@return` and `@param` tags
- Keep brief descriptions (1 line preferred)

```php
/**
 * Sets the mappings.
 *
 * @param array $mappings
 *
 * @return ArrayMapper fluent setter
 */
public function setMappings(array $mappings): ArrayMapper
```

### Class Properties
- Declare all properties with visibility keywords
- Use type hints where possible
- Document with PHPDoc for complex types

```php
/** @var array */
private $mappings = [];
/** @var bool */
private $hideEmptyValues = true;
```

### Error Handling
- Use exceptions for error conditions
- Use appropriate exception types from `lib/Exception/`
- Throw `InvalidArgumentException` for invalid input

```php
if (!is_object($mappable) && !is_array($mappable)) {
    throw new InvalidArgumentException('Argument must be either an array or an object');
}
```

### SugarCRM Entry Point
- Files that can be accessed directly must check for `sugarEntry`:

```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Legacy Code Patterns
- Many files use legacy patterns (dynamic properties, global state)
- Be cautious when refactoring legacy code
- Test thoroughly after any changes

### Getters/Setters
- Use fluent setters for chainability:

```php
public function setMappings($mappings): self
{
    $this->mappings = $mappings;
    return $this;
}
```

### Test Conventions
- Tests extend `SuitePHPUnitFrameworkTestCase`
- Use assertion methods: `self::assertEquals()`, `self::assertTrue()`, etc.
- Test file naming: `<ClassName>Test.php`

```php
use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

class ArrayMapperTest extends SuitePHPUnitFrameworkTestCase
{
    public function testMap(): void
    {
        // test code
    }
}
```

## Custom Module Development

### Module Location
- Standard modules: `modules/<ModuleName>/`
- Custom modules: `custom/Extension/application/Ext/`

### Bean Creation
- Use `BeanFactory::getBean()` for creating beans
- Always check for null returns

```php
$bean = BeanFactory::getBean('Accounts', $id);
if ($bean === null) {
    // handle error
}
```

### Database Queries
- Use `$db->query()` for complex queries
- Use `DBManagerFactory::getInstance()->getConnection()`
- Remember to escape user input

### PDF Templates Subpanels

To add subpanel support to PDF Templates:

1. **Get subpanels using SubPanelDefinitions** (NOT field_defs):
```php
require_once 'include/SubPanel/SubPanelDefinitions.php';
$subPanelDefinitions = new SubPanelDefinitions($bean);
$subpanels = $subPanelDefinitions->layout_defs['subpanel_setup'];
```

2. **Translate labels using title_key**:
```php
$label = translate($subpanelDef['title_key'], $bean->module_dir);
```

3. **Template syntax** (HTML comments):
```html
<!--$subpanel:relationship_name-->content<!--/$subpanel:relationship_name-->
```

See documentation at `/application/sinergia-crm-docs/03-features/PDF_Templates_Subpanels.md`.

## Best Practices

1. **Always run tests** after making changes
2. **Check coding standards** before submitting: `./vendor/bin/phpcs`
3. **Use type hints** whenever possible
4. **Keep methods small** and focused
5. commit **Write meaningful messages**
6. **Test edge cases** - CRM data can be unpredictable

## Common Gotchas

- Global state in legacy code
- Magic methods on SugarBean
- Database transactions require manual commit
- HTML encoding for XSS prevention in views
- DateTime handling across timezones
