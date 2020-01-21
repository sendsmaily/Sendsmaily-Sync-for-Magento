FROM alexcheng/magento:latest

# Set up build arguments.
ARG user_id
ARG group_id
ARG username
ARG group

# Isntall GIT. Required to allow symlinks with modman after SUPEE-9767v2 patch.
RUN apt-get update && apt-get install -y git

# Set up local user.
RUN if grep -q ${group} /etc/group; then \
       groupmod -g ${group_id} ${group}; \
    else \
       groupadd -f -g ${group_id} ${group}; \
    fi \
    && useradd -m -u ${user_id} -g ${group_id} ${username} -s /bin/bash

# Add local www-data group to the user
RUN usermod -a -G www-data ${username}
