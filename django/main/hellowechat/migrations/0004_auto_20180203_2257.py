# -*- coding: utf-8 -*-
# Generated by Django 1.11.3 on 2018-02-03 14:57
from __future__ import unicode_literals

from django.db import migrations, models
import uuid


class Migration(migrations.Migration):

    dependencies = [
        ('hellowechat', '0003_auto_20180203_2247'),
    ]

    operations = [
        migrations.AlterField(
            model_name='magicuser',
            name='openid',
            field=models.CharField(default='', max_length=255, primary_key=True),
        ),
        migrations.AlterField(
            model_name='magicuser',
            name='uuid',
            field=models.UUIDField(default=uuid.UUID('8b68dedd-5f09-40d5-bee7-eca4205f3b61'), editable=False, primary_key=True, serialize=False),
        ),
    ]
