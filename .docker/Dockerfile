FROM alexcheng/magento:1.9.3.8

# Set up build arguments.
ARG user_id
ARG group_id
ARG username
ARG group

# Install GIT. Required to allow symlinks with modman after SUPEE-9767v2 patch.
# Install sendmail for MailHog.
RUN apt-get update && apt-get install -y git wget \
   && wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
   && chmod +x mhsendmail_linux_amd64 \
   && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

# Set up local user.
RUN if grep -q ${group} /etc/group; then \
       groupmod -g ${group_id} ${group}; \
    else \
       groupadd -f -g ${group_id} ${group}; \
    fi \
    && useradd -m -u ${user_id} -g ${group_id} ${username} -s /bin/bash

# Add local www-data group to the user.
RUN usermod -a -G www-data ${username}
