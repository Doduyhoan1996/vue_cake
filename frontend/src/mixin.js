export default {
    methods: {
        showSuccessNotify(title, content) {
            this.$notify.closeAll()
            this.$notify({
                title: title ? title : 'Thành công',
                message: content ? content : 'Đã xử lý',
                type: 'success'
            })
        },
        showErrorNotify(title, content) {
            this.$notify.closeAll()
            this.$notify({
                title: title ? title : 'Cảnh báo',
                message: content ? content : 'Có lỗi xảy ra!',
                type: 'error'
            })
        },
        showMessage(type, content, html = false) {
            this.$message({
                showClose: true,
                dangerouslyUseHTMLString: html,
                message: content,
                type: type
            })
        },
        showConfirmMessage(type, title, content, handleOk, handleCancel) {
            title = title ? title : 'Cảnh báo'
			content = content ? content : 'Bạn có chắn chắn không?'
            this.$confirm(content, title, {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: type,
                dangerouslyUseHTMLString: true
            }).then(() => {
                handleOk()
            }).catch(() => {
                if (handleCancel) {
                    handleCancel()
                }
            });
        },
        showConfirmLoadingMessage(type, title, content, beforeClose, handleOk, handleCancel) {
			title = title ? title : 'Cảnh báo'
			content = content ? content : 'Bạn có chắn chắn không?'
			this.$confirm(content, title, {
				dangerouslyUseHTMLString: true,
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: type,
				beforeClose: (action, instance, done) => {
					beforeClose(action, instance, done)
				}
			}).then(() => {
				if (handleOk) {
					handleOk()
				}
			}).catch(() => {
				if (handleCancel) {
					handleCancel()
				}
			});
		},
        showInValidMessage(error) {
            var content = ''
            for (var key in error) {
                if (error.hasOwnProperty(key)) {
                    for (var k in error[key]) {
                        if (error[key].hasOwnProperty(k)) {
                            content += '• ' + key.toUpperCase() + ': ' + error[key][k] + '<br>'
                        }
                    }
                }
            }
            this.$message({
                showClose: true,
                dangerouslyUseHTMLString: true,
                message: content,
                type: 'error'
            })
        }
    }
}