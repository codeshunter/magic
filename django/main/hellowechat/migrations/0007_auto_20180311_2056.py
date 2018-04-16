# -*- coding: utf-8 -*-
# Generated by Django 1.11.3 on 2018-03-11 12:56
from __future__ import unicode_literals

from django.db import migrations, models
import uuid


class Migration(migrations.Migration):

    dependencies = [
        ('hellowechat', '0006_auto_20180308_1609'),
    ]

    operations = [
        migrations.CreateModel(
            name='MagicRoom',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('day', models.CharField(max_length=1)),
                ('detail', models.CharField(blank=True, max_length=100000, null=True)),
                ('updatetime', models.DateTimeField(auto_now=True)),
            ],
        ),
        migrations.AlterField(
            model_name='magicuser',
            name='sgrades',
            field=models.CharField(blank=True, max_length=10000, null=True),
        ),
        migrations.AlterField(
            model_name='magicuser',
            name='slessons',
            field=models.CharField(blank=True, max_length=10000, null=True),
        ),
        migrations.AlterField(
            model_name='magicuser',
            name='uuid',
            field=models.UUIDField(default=uuid.UUID('42b806c0-e102-431b-a5a2-b586ecd91b23'), editable=False, primary_key=True, serialize=False),
        ),
    ]