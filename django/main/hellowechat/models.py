import uuid as uuid
from django.db import models

# Create your models here.
class MagicUser (models.Model):
    uuid = models.AutoField(primary_key=True)
    openid = models.CharField(max_length=255, default="", unique=True)
    snumber = models.CharField(max_length=255, null=True, blank=True)
    spasswd = models.CharField(max_length=255, null=True, blank=True)
    sgrades = models.CharField(max_length=10000, null=True, blank=True)
    slessons = models.CharField(max_length=10000, null=True, blank=True)
    sjidian = models.CharField(max_length=255, null=True, blank=True)

class MagicRoom (models.Model):
    # id = models.AutoField(primary_key=True, default=0)
    details = models.TextField(null=True, blank=True)
