# -*- coding: utf-8 -*-
from django.conf.urls import include, url
from . import views

urlpatterns = [
    url(r'^$', views.index, name='magicindex'),
    url(r'^aboutus', views.aboutus, name='aboutus'),
]