# SiteGround Speed Optimizer Config Saver 🚀

![GitHub Actions](https://img.shields.io/badge/GitHub%20Actions-Enabled-brightgreen) ![Version](https://img.shields.io/badge/Version-1.0.0-blue) ![License](https://img.shields.io/badge/License-MIT-yellowgreen)

Welcome to the **SiteGround Speed Optimizer Config Saver** repository! This GitHub Action saves your SiteGround Speed Optimizer configuration to a versioned JSON file in your repository. This tool helps you manage configuration drift effectively and ensures your settings are always backed up and versioned.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)

## Features

- **Automatic Backups**: The action automatically saves your SiteGround Speed Optimizer settings to a JSON file.
- **Version Control**: Each configuration change is tracked, allowing you to revert to previous settings easily.
- **Simple Integration**: Integrate the action into your existing GitHub workflows with minimal effort.
- **Detect Configuration Drift**: Monitor changes in your configuration and address them proactively.

## Installation

To use the SiteGround Speed Optimizer Config Saver, you need to add it to your GitHub Actions workflow. Follow these steps:

1. Create a new workflow file in your repository, typically located in `.github/workflows/`.
2. Use the following code snippet to set up the action:

```yaml
name: Save SiteGround Config

on:
  push:
    branches:
      - main

jobs:
  save-config:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v2
      
      - name: Save SiteGround Speed Optimizer Config
        uses: BigSpace52/github-action-siteground-speed-optimizer-config-saver@v1.0.0
        with:
          siteground_api_key: ${{ secrets.SITEGROUND_API_KEY }}
```

3. Ensure you have the necessary API key stored in your repository secrets.

## Usage

Once the action is set up, it will run automatically on every push to the `main` branch. If you want to run it on different events, adjust the `on` section in your workflow file accordingly.

### Triggering the Action

You can trigger the action manually or through scheduled events. To run the action manually, you can use the "Run workflow" button in the GitHub Actions tab of your repository.

## Configuration

You can customize the action's behavior by passing different inputs. Here are the main configuration options:

- `siteground_api_key`: Your SiteGround API key. This is required for the action to access your settings.
- `output_file`: The name of the JSON file where the configuration will be saved. Default is `siteground-config.json`.

Example:

```yaml
- name: Save SiteGround Speed Optimizer Config
  uses: BigSpace52/github-action-siteground-speed-optimizer-config-saver@v1.0.0
  with:
    siteground_api_key: ${{ secrets.SITEGROUND_API_KEY }}
    output_file: custom-config.json
```

## Contributing

We welcome contributions to improve this action. Here’s how you can help:

1. **Fork the Repository**: Click the "Fork" button on the top right corner of the page.
2. **Create a Branch**: Use `git checkout -b feature/YourFeatureName` to create a new branch.
3. **Make Changes**: Implement your feature or fix.
4. **Commit Changes**: Use `git commit -m "Description of your changes"` to commit.
5. **Push to Your Branch**: Use `git push origin feature/YourFeatureName`.
6. **Create a Pull Request**: Go to the original repository and click "New Pull Request".

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

For any issues or questions, please check the [Releases](https://github.com/BigSpace52/github-action-siteground-speed-optimizer-config-saver/releases) section. You can also open an issue in the repository for help.

## Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [SiteGround API Documentation](https://developers.siteground.com/)

Thank you for using the SiteGround Speed Optimizer Config Saver! For the latest releases, please visit [here](https://github.com/BigSpace52/github-action-siteground-speed-optimizer-config-saver/releases) and download the latest version to get started.