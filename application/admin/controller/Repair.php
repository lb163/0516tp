<?php
namespace app\admin\controller;

class Repair extends Admin{
    public function index(){
        $pid = input('get.pid', 0);
        /* 获取频道列表 */
        $map  = array('status' => array('gt', -1));
        $list = \think\Db::name('repair')->where($map)->select();
        $this->assign('list', $list);
        $this->assign('pid', $pid);
        $this->assign('meta_title' , '导航管理');
        return $this->fetch();
    }
    public function add()
    {
        if (request()->isPost()) {
            $Channel = model('repair');
            $post_data = \think\Request::instance()->post();

            $data = $Channel->create($post_data);
            if ($data) {
                $this->success('新增成功', url('index'));
                //记录行为
//                action_log('update_channel', 'channel', $data->id, UID);
            } else {
                $this->error($Channel->getError());
            }
        } else {
            $pid = input('pid', 0);
            //获取父导航
            if (!empty($pid)) {
                $parent = \think\Db::name('Channel')->where(array('id' => $pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', null);
            $this->assign('meta_title', '新增导航');
            return $this->fetch('edit');
        }
    }
    public function edit($id){
//        var_dump($id);exit();
        if($this->request->isPost()){
            $postdata = \think\Request::instance()->post();
//            var_dump($postdata);exit();
            $Channel = \think\Db::name("repair");
//            var_dump($Channel);exit();
            $data = $Channel->update($postdata);
            if($data !== false){
                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = \think\Db::name('repair')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            return $this->fetch('');
        }
    }
    public function del(){
        $id = array_unique((array)input('id/a',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(\think\Db::name('repair')->where($map)->delete()){
            //记录行为
            action_log('update_repair', 'repair', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}