#!/bin/bash
# Fetch secret value using AWS CLI (ensure IAM role/instance profile has permission)
SECRET_JSON=$(aws secretsmanager get-secret-value \
  --secret-id slydev \
  --query SecretString \
  --output text)
# Export each field as environment variable
export DB_HOST=$(echo $SECRET_JSON | jq -r .DB_HOST)
export DB_USER=$(echo $SECRET_JSON | jq -r .DB_USER)
export DB_PASS=$(echo $SECRET_JSON | jq -r .DB_PASS)
export DB_NAME=$(echo $SECRET_JSON | jq -r .DB_NAME)

echo "extablishing connection to $DB_HOST"












