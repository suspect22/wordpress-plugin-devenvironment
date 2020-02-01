ARG DOCKERFILE_ARGS_WORDPRESS_VERSION
FROM wordpress:${DOCKERFILE_ARGS_WORDPRESS_VERSION}

# Install Less for WP-CLI
RUN apt-get update && apt-get -y install less

# Install WP-CLI
RUN curl -s -o /usr/local/bin/wp \
    https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp
