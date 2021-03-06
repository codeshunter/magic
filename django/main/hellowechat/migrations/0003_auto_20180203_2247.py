# -*- coding: utf-8 -*-
# Generated by Django 1.11.3 on 2018-02-03 14:47
from __future__ import unicode_literals

from django.db import migrations, models
import uuid


class Migration(migrations.Migration):

    dependencies = [
        ('hellowechat', '0002_auto_20180203_2240'),
    ]

    operations = [
        migrations.AddField(
            model_name='magicuser',
            name='openid',
            field=models.CharField(default='', max_length=255),
        ),
        migrations.AlterField(
            model_name='magicuser',
            name='uuid',
            field=models.UUIDField(default=uuid.UUID('5108cd93-7ad9-4381-a07d-f3a59fb7788c'), editable=False, primary_key=True, serialize=False),
        ),
    ]
