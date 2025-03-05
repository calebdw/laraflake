# CONTRIBUTING

Contributions are welcome, and are accepted via pull requests.
Please review these guidelines before submitting any pull requests.

## Process

1. Fork the project
1. Create a new branch
1. Code, test, commit and push
1. Open a pull request detailing your changes.

## Guidelines

- Please follow the [PSR-12 Coding Style Guide](http://www.php-fig.org/psr/psr-12/), enforced by [Pint](https://github.com/laravel/pint).
- Send a coherent commit history, making sure each individual commit in your pull request is meaningful.
- You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to avoid merge conflicts.
- Please remember that we follow [SemVer](http://semver.org/).

## Setup

Clone your fork, then install the dependencies:

```bash
    composer update
```

## Development Container

For a consistent development environment, you can use the provided development container:

1. Ensure you have [Docker](https://www.docker.com/get-started) and [Visual Studio Code](https://code.visualstudio.com/) with the [Remote - Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) extension installed
2. Open the project in Visual Studio Code
3. Click on the green button in the bottom-left corner (or press F1 and select "Remote-Containers: Reopen in Container")
4. VSCode will build the container and provide a fully configured environment

For more details, please refer to the [devcontainer README](.devcontainer/README.md).

## Tests

Run all tests:

```bash
    composer test
```

Linting:

```bash
    composer test:lint
```

Static analysis:

```bash
    composer test:static
```

PHPUnit tests:

```bash
    composer test:coverage
```
