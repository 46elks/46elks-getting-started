#pragma once

typedef struct string_buffer {
    char* string;
    size_t len;
    struct string_buffer* next;
} string_buffer;

bool verify_phonenumber(char*);
void elks_api_connect(char*, char*, char*);
size_t elks_store_response(char*, size_t, size_t, void*);
void elks_print_sb(string_buffer*);
void elks_free_sb(string_buffer*);
